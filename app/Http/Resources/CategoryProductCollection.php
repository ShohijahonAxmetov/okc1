<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            'current_page' => $currentPage,
            'data' => [
                [
                    'id' => $id,
                    'brand_id' => $brandId,
                    'title' => [
                        'ru' => $title->ru,
                        'uz' => $title->uz,
                    ],
                    'desc' => [
                        'ru' => $desc->ru,
                        'uz' => $desc->uz,
                    ],
                    'how_to_use' => [
                        'ru' => $how_to_use->ru,
                        'uz' => $how_to_use->uz,
                    ],
                    'rating' => $rating,
                    'number_of_ratings' => $number_of_ratings,
                    'vendor_code' => $vendor_code,
                    'meta_keywords' => [
                        'ru' => $meta_keywords->ru,
                        'uz' => $meta_keywords->uz,
                    ],
                    'is_popular' => $is_popular,
                    'is_active' => $is_active,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'meta_title' => [
                        'ru' => $meta_title->ru,
                        'uz' => $meta_title->uz,
                    ],
                    'meta_desc' => [
                        'ru' => $meta_desc->ru,
                        'uz' => $meta_desc->uz,
                    ],
                    'default_variation' => [
                        'id' => $defaultVariation->id,
                        'product_id' => $defaultVariation->product_id,
                        'color_id' => $defaultVariation->color_id,
                        'venkon_id' => $defaultVariation->venkon_id,
                        'product_code' => $defaultVariation->product_code,
                        'remainder' => $defaultVariation->remainder,
                        'price' => $defaultVariation->price,
                        'discount_price' => $defaultVariation->discount_price,
                        'is_default' => $defaultVariation->is_default,
                        'is_available' => $defaultVariation->is_available,
                        'with_discount' => $defaultVariation->with_discount,
                        'is_active' => $defaultVariation->is_active,
                        'slug' => $defaultVariation->slug,
                        'created_at' => $defaultVariation->created_at,
                        'updated_at' => $defaultVariation->updated_at,
                        'integration_id' => $defaultVariation->integration_id,
                        'spic_id' => $defaultVariation->spic_id,
                        'package_code' => $defaultVariation->package_code,
                        'express24_id' => $defaultVariation->express24_id,
                        'product_variation_images' => [
                            [
                                'id' => $imageId,
                                'product_variation_id' => $product_variation_id,
                                'img' => $img,
                                'created_at' => $created_at,
                                'updated_at' => $updated_at,
                                'min_img' => $min_img,
                                'md_img' => $md_img,
                                'real_img' => $real_img,
                            ]
                        ],
                    ],
                    'remainder' => $remainder,
                    'brand' => [
                        'id' => '',
                        'venkon_id' => '',
                        'title' => '',
                        'desc' => [

                        ],
                        'img' => '',
                        'position' => '',
                        'is_active' => '',
                        'link' => '',
                        'slug' => '',
                        'created_at' => '',
                        'updated_at' => '',
                        'integration_id' => '',
                    ]
                ]
            ]
        ];
    }
}
