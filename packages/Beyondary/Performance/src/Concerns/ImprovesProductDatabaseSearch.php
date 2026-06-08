<?php

namespace Beyondary\Performance\Concerns;

use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Enums\AttributeTypeEnum;

trait ImprovesProductDatabaseSearch
{
    /**
     * Search product from database with FULLTEXT/LIKE and exact url_key matching.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchFromDatabase(array $params = [])
    {
        $params['url_key'] ??= null;

        if (! empty($params['query'])) {
            $params['name'] = $params['query'];
        }

        $query = $this->with([
            'attribute_family',
            'images',
            'videos',
            'attribute_values',
            'price_indices',
            'inventory_indices',
            'reviews',
            'variants',
            'variants.attribute_family',
            'variants.attribute_values',
            'variants.price_indices',
            'variants.inventory_indices',
        ])->scopeQuery(function ($query) use ($params) {
            $prefix = DB::getTablePrefix();

            $qb = $query->distinct()
                ->select('products.*')
                ->leftJoin('products as variants', DB::raw('COALESCE('.$prefix.'variants.parent_id, '.$prefix.'variants.id)'), '=', 'products.id')
                ->leftJoin('product_price_indices', function ($join) {
                    $customerGroup = $this->customerRepository->getCurrentGroup();

                    $join->on('products.id', '=', 'product_price_indices.product_id')
                        ->where('product_price_indices.customer_group_id', $customerGroup->id);
                });

            if (! empty($params['category_id'])) {
                $qb->leftJoin('product_categories', 'product_categories.product_id', '=', 'products.id')
                    ->whereIn('product_categories.category_id', explode(',', $params['category_id']));
            }

            if (! empty($params['channel_id'])) {
                $qb->leftJoin('product_channels', 'products.id', '=', 'product_channels.product_id')
                    ->where('product_channels.channel_id', explode(',', $params['channel_id']));
            }

            if (! empty($params['type'])) {
                $qb->where('products.type', $params['type']);

                if (
                    $params['type'] === 'simple'
                    && ! empty($params['exclude_customizable_products'])
                ) {
                    $qb->leftJoin('product_customizable_options', 'products.id', '=', 'product_customizable_options.product_id')
                        ->whereNull('product_customizable_options.id');
                }
            }

            if (! empty($params['price'])) {
                $priceRange = explode(',', $params['price']);

                $qb->whereBetween('product_price_indices.min_price', [
                    core()->convertToBasePrice(current($priceRange)),
                    core()->convertToBasePrice(end($priceRange)),
                ]);
            }

            $filterableAttributes = $this->attributeRepository->getProductDefaultAttributes(array_keys($params));

            foreach ($filterableAttributes as $priceAttribute) {
                if (
                    $priceAttribute->type !== AttributeTypeEnum::PRICE->value
                    || $priceAttribute->code === 'price'
                    || empty($params[$priceAttribute->code])
                ) {
                    continue;
                }

                $range = explode(',', $params[$priceAttribute->code]);
                $alias = $priceAttribute->code.'_price_range_values';

                $qb->leftJoin('product_attribute_values as '.$alias, function ($join) use ($alias, $priceAttribute) {
                    $join->on('products.id', '=', $alias.'.product_id')
                        ->where($alias.'.attribute_id', $priceAttribute->id);
                })->whereBetween($alias.'.float_value', [
                    core()->convertToBasePrice(current($range)),
                    core()->convertToBasePrice(end($range)),
                ]);
            }

            $attributes = $filterableAttributes->whereIn('code', [
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            foreach ($attributes as $attribute) {
                $alias = $attribute->code.'_product_attribute_values';

                $qb->leftJoin('product_attribute_values as '.$alias, 'products.id', '=', $alias.'.product_id')
                    ->where($alias.'.attribute_id', $attribute->id);

                if ($attribute->code == 'name') {
                    $synonyms = $this->searchSynonymRepository->getSynonymsByQuery(urldecode($params['name']));

                    $this->applyTextValueSearch($qb, $alias, $synonyms);
                } elseif ($attribute->code == 'url_key') {
                    if (empty($params['url_key'])) {
                        $qb->whereNotNull($alias.'.text_value');
                    } else {
                        $qb->where($alias.'.text_value', urldecode($params['url_key']));
                    }
                } else {
                    if (is_null($params[$attribute->code])) {
                        continue;
                    }

                    $qb->where($alias.'.'.$attribute->column_name, 1);
                }
            }

            $attributes = $filterableAttributes->whereNotIn('code', [
                'name',
                'status',
                'visible_individually',
                'url_key',
            ])->filter(function ($attribute) {
                return $attribute->type !== AttributeTypeEnum::PRICE->value;
            });

            if ($attributes->isNotEmpty()) {
                $qb->where(function ($filterQuery) use ($qb, $params, $attributes, $prefix) {
                    $aliases = [
                        'products' => 'product_attribute_values',
                        'variants' => 'variant_attribute_values',
                    ];

                    foreach ($aliases as $table => $tableAlias) {
                        $filterQuery->orWhere(function ($subFilterQuery) use ($qb, $params, $attributes, $prefix, $table, $tableAlias) {
                            foreach ($attributes as $attribute) {
                                $alias = $attribute->code.'_'.$tableAlias;

                                $qb->leftJoin('product_attribute_values as '.$alias, function ($join) use ($table, $alias, $attribute) {
                                    $join->on($table.'.id', '=', $alias.'.product_id');

                                    $join->where($alias.'.attribute_id', $attribute->id);
                                });

                                if (in_array($attribute->type, [
                                    AttributeTypeEnum::CHECKBOX->value,
                                    AttributeTypeEnum::MULTISELECT->value,
                                ])) {
                                    $paramValues = explode(',', $params[$attribute->code]);

                                    $subFilterQuery->where(function ($query) use ($paramValues, $alias, $attribute, $prefix) {
                                        foreach ($paramValues as $value) {
                                            $query->orWhereRaw("FIND_IN_SET(?, {$prefix}{$alias}.{$attribute->column_name})", [$value]);
                                        }
                                    });
                                } else {
                                    $subFilterQuery->whereIn($alias.'.'.$attribute->column_name, explode(',', $params[$attribute->code]));
                                }
                            }
                        });
                    }
                });

                $qb->groupBy('products.id');
            }

            $sortOptions = $this->getSortOptions($params);

            if ($sortOptions['order'] != 'rand') {
                $attribute = $this->attributeRepository->findOneByField('code', $sortOptions['sort']);

                if ($attribute) {
                    if ($attribute->code === 'price') {
                        $qb->orderBy('product_price_indices.min_price', $sortOptions['order']);
                    } else {
                        $alias = 'sort_product_attribute_values';

                        $qb->leftJoin('product_attribute_values as '.$alias, function ($join) use ($alias, $attribute) {
                            $join->on('products.id', '=', $alias.'.product_id')
                                ->where($alias.'.attribute_id', $attribute->id);

                            if ($attribute->value_per_channel) {
                                if ($attribute->value_per_locale) {
                                    $join->where($alias.'.channel', core()->getRequestedChannelCode())
                                        ->where($alias.'.locale', core()->getRequestedLocaleCode());
                                } else {
                                    $join->where($alias.'.channel', core()->getRequestedChannelCode());
                                }
                            } else {
                                if ($attribute->value_per_locale) {
                                    $join->where($alias.'.locale', core()->getRequestedLocaleCode());
                                }
                            }
                        })
                            ->orderBy($alias.'.'.$attribute->column_name, $sortOptions['order']);
                    }
                } else {
                    $qb->orderBy('products.created_at', $sortOptions['order']);
                }
            } else {
                return $qb->inRandomOrder();
            }

            return $qb->groupBy('products.id');
        });

        $limit = $this->getPerPageLimit($params);

        return $query->paginate($limit);
    }

    /**
     * Apply OR-based text search across synonym terms.
     */
    protected function applyTextValueSearch($query, string $alias, array $terms): void
    {
        $terms = collect($terms)
            ->map(fn ($term) => trim(urldecode($term)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($terms)) {
            return;
        }

        $tablePrefix = DB::getTablePrefix();

        $query->where(function ($subQuery) use ($alias, $terms, $tablePrefix) {
            foreach ($terms as $term) {
                $subQuery->orWhere(function ($termQuery) use ($alias, $term, $tablePrefix) {
                    $this->applySingleTextValueSearch($termQuery, $alias, $term, $tablePrefix);
                });
            }
        });
    }

    /**
     * Search a single term using FULLTEXT when possible, otherwise prefix/substring LIKE.
     */
    protected function applySingleTextValueSearch($query, string $alias, string $term, string $tablePrefix): void
    {
        $booleanTerm = $this->prepareFullTextBooleanTerm($term);

        if ($this->shouldUseFullTextSearch($term) && $booleanTerm !== '') {
            $query->whereRaw(
                "MATCH({$tablePrefix}{$alias}.text_value) AGAINST(? IN BOOLEAN MODE)",
                [$booleanTerm]
            );

            return;
        }

        $query->where(function ($fallbackQuery) use ($alias, $term) {
            $fallbackQuery->where($alias.'.text_value', 'like', $term.'%');

            if (! $this->isPrefixSearchSufficient($term)) {
                $fallbackQuery->orWhere($alias.'.text_value', 'like', '%'.$term.'%');
            }
        });
    }

    /**
     * FULLTEXT works for latin terms >= ft_min_word_len; CJK/Thai need LIKE fallback.
     */
    protected function shouldUseFullTextSearch(string $term): bool
    {
        if (preg_match('/\p{Thai}/u', $term)
            || preg_match('/\p{Han}/u', $term)
            || preg_match('/\p{Hiragana}/u', $term)
            || preg_match('/\p{Katakana}/u', $term)) {
            return false;
        }

        return mb_strlen($term) >= 4;
    }

    /**
     * Skip substring scan when a prefix match is enough for latin terms.
     */
    protected function isPrefixSearchSufficient(string $term): bool
    {
        return ! preg_match('/\p{Thai}/u', $term)
            && ! preg_match('/\p{Han}/u', $term)
            && mb_strlen($term) >= 4;
    }

    /**
     * Build a BOOLEAN MODE query: +word* requires each word as a prefix match.
     */
    protected function prepareFullTextBooleanTerm(string $term): string
    {
        $term = preg_replace('/[+\-><()~*"@]+/', ' ', $term) ?? '';
        $term = preg_replace('/\s+/', ' ', trim($term)) ?? '';

        if ($term === '') {
            return '';
        }

        return collect(explode(' ', $term))
            ->filter(fn (string $word) => mb_strlen($word) >= 4)
            ->map(fn (string $word) => '+'.$word.'*')
            ->join(' ');
    }
}
