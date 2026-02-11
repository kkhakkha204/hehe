<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lập Trình Web',
                'slug' => 'lap-trinh-web',
                'seo_title' => 'Khóa học Lập Trình Web | UIA Academy',
                'seo_description' => 'Học lập trình web từ cơ bản đến nâng cao với HTML, CSS, JavaScript, PHP, Laravel và nhiều hơn nữa.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Lập Trình Mobile',
                'slug' => 'lap-trinh-mobile',
                'seo_title' => 'Khóa học Lập Trình Mobile | UIA Academy',
                'seo_description' => 'Xây dựng ứng dụng di động với React Native, Flutter, Swift và Android native.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Thiết Kế Đồ Họa',
                'slug' => 'thiet-ke-do-hoa',
                'seo_title' => 'Khóa học Thiết Kế Đồ Họa | UIA Academy',
                'seo_description' => 'Làm chủ Photoshop, Illustrator, Figma để tạo ra những thiết kế chuyên nghiệp.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Marketing Online',
                'slug' => 'marketing-online',
                'seo_title' => 'Khóa học Marketing Online | UIA Academy',
                'seo_description' => 'SEO, Facebook Ads, Google Ads, Email Marketing và các chiến lược marketing hiệu quả.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Kỹ Năng Mềm',
                'slug' => 'ky-nang-mem',
                'seo_title' => 'Khóa học Kỹ Năng Mềm | UIA Academy',
                'seo_description' => 'Giao tiếp, thuyết trình, làm việc nhóm và các kỹ năng cần thiết cho sự nghiệp.',
                'is_active' => false, // Ví dụ danh mục bị ẩn
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
