<?php

namespace App\Entity;

enum UserStatus: string
{
    case Admin = 'admin';
    case Employee = 'employee';
    case Client = 'client';
}


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