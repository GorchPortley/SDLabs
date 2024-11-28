<?php
    use function Laravel\Folio\{name};
    name('blog');

    $posts = \Wave\Post::orderBy('created_at', 'DESC')->paginate(6);
    $categories = \Wave\Category::all();
?>

<x-layouts.marketing
    :seo="[
        'title' => 'Blog',
        'description' => 'Our Blog',
    ]"
>
    <x-container class="py-2 sm:py-10">
        <div class="relative pt-6">
            <x-marketing.heading
                title="From The Blog"
                description="Check out some of our latest blog posts below."
                align="left"
            />
            
            <x-marketing.blog-categories />

            <div class="grid gap-5 mx-auto mt-10 sm:grid-cols-2 lg:grid-cols-3">
                <x-marketing.posts-loop :posts="$posts" />
            </div>
        </div>

        <div class="flex justify-center my-10">
            {{ $posts->links('theme::partials.pagination') }}
        </div>

    </x-container>
</x-layouts.marketing>
