<?php

namespace App\Entity\Enums;

enum ItemCategory: string
{
    case Dress = 'dress';
    case Pant = 'pant';
    case Necklaces = 'necklaces';
    case Shoes = 'shoes';
    case Hat = 'hat';
    case Bag = 'bag';
    case Earrings = 'earrings';
    case Watch = 'watch';
}