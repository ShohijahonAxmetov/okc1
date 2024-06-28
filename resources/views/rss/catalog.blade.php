<?php
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<yml_catalog date="{{$date}}">
    <shop>
        <name>{{$shop['name']}}</name>
        <company>{{$shop['company']}}</company>
        <url>{{$shop['url']}}</url>
        <categories>
            @foreach($categories as $category)
                @if(!isset($category['attributes']['parentId']))
                <category id="{{$category['attributes']['id']}}">{{$category['name']}}</category>
                @else
                <category id="{{$category['attributes']['id']}}" parentId="{{$category['attributes']['parentId']}}">{{$category['name']}}</category>
                @endif
            @endforeach
        </categories>
        <offers>
            @foreach($offers as $offer)
            <offer id="{{$offer['sku']}}">
                <name>{{$offer['name']}}</name>
                <quantityStock>{{$offer['quantityStock']}}</quantityStock>
                <brand>{{$offer['brand']}}</brand>
                <description>{{$offer['description']}}</description>
                <picture>{{$offer['picture']}}</picture>
                <price>{{$offer['price']}}</price>
                <sku>{{$offer['sku']}}</sku>
                <enabled>{{$offer['enabled']}}</enabled>
                <categoryId>{{$offer['categoryId']}}</categoryId>
            </offer>
            @endforeach
        </offers>
    </shop>
</yml_catalog>