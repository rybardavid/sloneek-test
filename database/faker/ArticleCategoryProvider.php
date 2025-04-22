<?php

namespace Database\Faker;

use Faker\Generator;
use Faker\Provider\Base;
use LogicException;

class ArticleCategoryProvider extends Base
{
    /** @var string[] */
    private const array CATEGORIES = [
        'Health & Wellness',
        'Technology',
        'Food & Recipes',
        'Personal Development',
        'Fitness & Exercise',
        'Travel',
        'Business & Finance',
        'Beauty & Fashion',
        'Parenting',
        'Lifestyle',
        'Education',
        'Home Decor',
        'Photography',
        'Sports',
        'Marketing & SEO',
        'Mental Health',
        'Science & Innovation',
        'Environment & Sustainability',
        'Arts & Culture',
        'History',
        'Current Affairs & News',
        'Music',
        'Social Media & Networking',
        'Finance & Investments',
        'Entrepreneurship',
        'Food & Drink',
        'Relationships',
        'DIY & Crafts',
        'Auto & Motor Vehicles',
        'Tech Reviews & Gadgets',
        'Web Development & Coding',
        'Gaming',
        'Real Estate',
        'Career & Job Search',
        'Productivity & Time Management',
        'Marketing Strategies',
        'Photography Tips',
        'Pet Care & Animals',
        'Writing & Literature',
        'Parenting Tips',
        'Luxury & Lifestyle',
        'Wedding & Events',
        'Travel Destinations & Guides',
        'Book Reviews',
        'Education & Learning',
        'Meditation & Mindfulness',
        'Healthy Recipes',
        'Finance Tips & Advice',
        'Travel Hacks',
        'Local News & Events',
    ];

    /** @var string[] */
    private array $categories;

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
        $this->categories = self::CATEGORIES;
    }

    public function articleCategory(): string
    {
        $category = array_pop($this->categories);
        if ($category === null) {
            throw new LogicException('The maximum of article categories has been exceeded');
        }

        return $category;
    }
}
