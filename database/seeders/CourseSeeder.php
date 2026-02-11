<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Lấy ID hợp lệ cho Category và Author (User)
        // Nếu bảng rỗng, tạo tạm 1 dòng để tránh lỗi Foreign Key
        $categoryId = DB::table('categories')->value('id');
        if (!$categoryId) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => 'Lập trình Web',
                'slug' => 'lap-trinh-web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $authorId = DB::table('users')->value('id');
        if (!$authorId) {
            $authorId = DB::table('users')->insertGetId([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // 2. Chuẩn bị dữ liệu cho 15 khóa học
        $courses = [];
        $now = Carbon::now();
        $startId = 4;
        $count = 15;

        for ($i = 0; $i < $count; $i++) {
            $currentId = $startId + $i; // 4, 5, 6...
            $title = "Khóa học Fullstack Laravel thực chiến - Phần " . ($i + 1);

            // Random trạng thái hiển thị
            $price = rand(500, 3000) * 1000;
            $isPublished = true;

            $courses[] = [
                'id' => $currentId,
                'title' => $title,
                'slug' => Str::slug($title) . '-' . $currentId, // Slug unique
                'category_id' => $categoryId,
                'author_id' => $authorId,
                'thumbnail' => null, // Hoặc điền link ảnh nếu muốn
                'price' => $price,
                'sale_price' => ($i % 3 == 0) ? $price * 0.8 : null, // Sale 20% cho một số khóa
                'duration' => rand(120, 600), // Phút
                'description' => "Đây là nội dung mô tả chi tiết cho khóa học ID $currentId. Học Laravel 11 kết hợp Filament V3.",
                'current_students' => rand(10, 200),
                'views' => rand(100, 5000),
                'is_published' => $isPublished,
                'is_featured' => ($i % 5 == 0),
                'sort_order' => 0,
                'seo_title' => $title,
                'seo_description' => "Học lập trình web hiệu quả với khóa học $title",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 3. Insert dữ liệu
        // Dùng insertOrIgnore để nếu ID 4 đã tồn tại thì không bị crash app, chỉ bỏ qua dòng đó
        DB::table('courses')->insertOrIgnore($courses);

        echo "Đã seed xong " . count($courses) . " khóa học (ID $startId -> " . ($startId + $count - 1) . ")\n";
    }
}
