<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categories = [
            ['Electronics', 'electronics', 'Latest gadgets and electronic devices'],
            ['Fashion', 'fashion', 'Trendy clothing and accessories'],
            ['Home & Garden', 'home-garden', 'Furniture and garden supplies'],
            ['Sports', 'sports', 'Sports equipment and fitness gear'],
            ['Books', 'books', 'Books for all interests'],
        ];

        $categoryObjects = [];
        foreach ($categories as $catData) {
            $category = new Category();
            $category->setNom($catData[0]);
            $category->setSlug($catData[1]);
            $category->setDescription($catData[2]);
            $manager->persist($category);
            $categoryObjects[$catData[0]] = $category;
        }

        // Création des produits
        $products = [
            ['Wireless Headphones', 'wireless-headphones', 'Premium sound quality with noise cancellation', 79.99, 50, 'item.png', 'Electronics'],
            ['Wireless Headphones', 'wireless-headphones', 'Premium sound quality with noise cancellation', 79.99, 50, 'item.png', 'Electronics'],

            ['Wireless Headphones', 'wireless-headphones', 'Premium sound quality with noise cancellation', 79.99, 50, 'item.png', 'Electronics'],
            ['Bluetooth Speaker', 'bluetooth-speaker', 'Portable wireless speaker', 59.99, 30, 'item.png', 'Electronics'],
            ['Smartphone Stand', 'smartphone-stand', 'Adjustable phone stand', 19.99, 100, 'item.png', 'Electronics'],
            ['Mechanical Keyboard', 'mechanical-keyboard', 'RGB mechanical gaming keyboard', 89.99, 25, 'item.png', 'Electronics'],
            ['Classic Leather Jacket', 'classic-leather-jacket', 'Genuine leather jacket', 149.99, 20, 'item.png', 'Fashion'],
            ['Wireless Mouse', 'wireless-mouse', 'Ergonomic wireless mouse', 29.99, 45, 'mouse.png', 'Electronics'],
            ['Mechanical Keyboard', 'mechanical-keyboard', 'RGB mechanical gaming keyboard', 89.99, 25, 'item.png', 'Electronics'],
            ['Mechanical Keyboard', 'mechanical-keyboard', 'RGB mechanical gaming keyboard', 89.99, 25, 'item.png', 'Electronics'],
            ['Mechanical Keyboard', 'mechanical-keyboard', 'RGB mechanical gaming keyboard', 89.99, 25, 'item.png', 'Electronics'],
            ['Mechanical Keyboard', 'mechanical-keyboard', 'RGB mechanical gaming keyboard', 89.99, 25, 'item.png', 'Electronics'],
            ['Mechanical Keyboard', 'mechanical-keyboard', 'RGB mechanical gaming keyboard', 89.99, 25, 'item.png', 'Electronics'],

            ['Yoga Mat', 'yoga-mat', 'Non-slip exercise mat', 29.99, 60, 'item.png', 'Sports'],
        ];

        foreach ($products as $prodData) {
            $product = new Product();
            $product->setNom($prodData[0]);
            $product->setSlug($prodData[1]);
            $product->setDescription($prodData[2]);
            $product->setPrix($prodData[3]);
            $product->setStock($prodData[4]);
            $product->setImage($prodData[5]);
            $product->setCategory($categoryObjects[$prodData[6]]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}