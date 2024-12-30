<?php
declare(strict_types=1);

namespace Mlab\BudetControl\Entities;

use Mlabfactory\WordPress\Entities\Category;

class Categories {

    private array $categories = [];

    public function addCategory(string $name, Category $category): self
    {   
        $key = md5($name);
        $this->categories[$key] = $category;
        return $this;
    }

    /**
     * Get the value of categories
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getbyname(string $name): Category
    {
        $key = md5($name);
        return $this->categories[$key];
    }
}