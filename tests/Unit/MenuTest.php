<?php

declare(strict_types=1);

use Performing\Harmony\Components\Menu\Menu;
use Performing\Harmony\Components\Menu\MenuItem;
use Performing\Harmony\Components\Menu\Navigation;

it('can create a menu with title', function () {
    $menu = Menu::make('Main Menu');

    expect($menu->getTitle())->toBe('Main Menu');
});

it('can add children to menu', function () {
    $item1 = MenuItem::make('Home');
    $item2 = MenuItem::make('About');

    $menu = Menu::make('Nav')->children($item1, $item2);

    expect($menu->getChildren())->toHaveCount(2);
});

it('hidden children produce empty array in toArray', function () {
    $item1 = MenuItem::make('Visible');
    $item2 = MenuItem::make('Hidden')->when(false);

    $menu = Menu::make('Nav')->children($item1, $item2);

    $array = $menu->toArray();

    expect($menu->getChildren())->toHaveCount(1);
});

it('creates a menu item with title', function () {
    $item = MenuItem::make('Dashboard');

    expect($item->getTitle())->toBe('Dashboard');
});

it('can set icon on menu item', function () {
    $item = MenuItem::make('Settings')->icon('cog');

    expect($item->toArray())->toHaveKey('icon', 'cog');
});

it('can create a navigation component', function () {
    $nav = Navigation::make('Sidebar');

    expect($nav->getTitle())->toBe('Sidebar');
});

it('navigation can have children', function () {
    $item = MenuItem::make('Home');
    $nav = Navigation::make('Sidebar')->children($item);

    expect($nav->getChildren())->toHaveCount(1);
});
