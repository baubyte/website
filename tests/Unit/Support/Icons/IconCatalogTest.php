<?php

namespace Tests\Unit\Support\Icons;

use App\Support\Icons\IconCatalog;
use Tests\TestCase;

/**
 * Exercises `IconCatalog` against the real vendored
 * `resources/icons/devicon.json` — no fixtures/mocks, since the whole point
 * of this class is to be a thin, trustworthy reader over that exact file.
 */
class IconCatalogTest extends TestCase
{
    public function test_has_returns_true_for_a_known_icon_id(): void
    {
        $this->assertTrue(IconCatalog::has('devicon:laravel'));
    }

    public function test_has_returns_false_for_an_unknown_id(): void
    {
        $this->assertFalse(IconCatalog::has('devicon:this-icon-does-not-exist'));
    }

    public function test_has_returns_false_for_a_null_id(): void
    {
        $this->assertFalse(IconCatalog::has(null));
    }

    public function test_has_returns_false_for_an_id_under_a_different_prefix(): void
    {
        $this->assertFalse(IconCatalog::has('mdi:laravel'));
    }

    public function test_resolve_returns_the_raw_icon_data_for_a_known_id(): void
    {
        $icon = IconCatalog::resolve('devicon:laravel');

        $this->assertIsArray($icon);
        $this->assertArrayHasKey('body', $icon);
        $this->assertArrayHasKey('width', $icon);
        $this->assertArrayHasKey('height', $icon);
        $this->assertStringContainsString('<path', $icon['body']);
    }

    public function test_resolve_returns_null_for_an_unknown_id(): void
    {
        $this->assertNull(IconCatalog::resolve('devicon:this-icon-does-not-exist'));
    }

    public function test_resolve_returns_null_for_a_null_id(): void
    {
        $this->assertNull(IconCatalog::resolve(null));
    }

    public function test_resolve_follows_an_alias_to_its_parent_icon(): void
    {
        // `web3` is a real alias of `web3js` in the vendored devicon
        // collection (see `resources/icons/devicon.json`'s `aliases` key).
        $alias = IconCatalog::resolve('devicon:web3');
        $parent = IconCatalog::resolve('devicon:web3js');

        $this->assertNotNull($alias);
        $this->assertSame($parent, $alias);
    }

    public function test_search_finds_known_icons_by_substring(): void
    {
        $results = IconCatalog::search('larav');

        $this->assertNotEmpty($results);
        $this->assertContains(['id' => 'devicon:laravel', 'label' => 'Laravel'], $results);
    }

    public function test_search_respects_the_limit(): void
    {
        $results = IconCatalog::search('a', 5);

        $this->assertCount(5, $results);
    }

    public function test_search_returns_empty_array_for_a_term_matching_nothing(): void
    {
        $this->assertSame([], IconCatalog::search('this-does-not-match-any-icon'));
    }

    public function test_label_for_returns_a_readable_label_for_a_known_id(): void
    {
        $this->assertSame('Laravel', IconCatalog::labelFor('devicon:laravel'));
    }

    public function test_label_for_returns_null_for_an_unknown_id(): void
    {
        $this->assertNull(IconCatalog::labelFor('devicon:this-icon-does-not-exist'));
    }
}
