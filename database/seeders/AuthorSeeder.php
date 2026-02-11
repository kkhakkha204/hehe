<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'name' => 'Nguyễn Văn An',
                'avatar' => null, // Sẽ dùng default avatar
                'bio' => 'Lập trình viên Full-stack với 10 năm kinh nghiệm. Chuyên về Laravel, React và Vue.js.',
                'email' => 'nguyenvanan@uia.test',
                'facebook' => 'https://facebook.com/nguyenvanan',
                'linkedin' => 'https://linkedin.com/in/nguyenvanan',
                'website' => 'https://nguyenvanan.dev',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Trần Thị Bình',
                'avatar' => null,
                'bio' => 'Chuyên gia UI/UX với hơn 8 năm kinh nghiệm. Đã làm việc cho nhiều startup và doanh nghiệp lớn.',
                'email' => 'tranthibinh@uia.test',
                'facebook' => null,
                'linkedin' => 'https://linkedin.com/in/tranthibinh',
                'website' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Lê Minh Cường',
                'avatar' => null,
                'bio' => 'Marketing Digital với chứng chỉ Google Ads & Facebook Blueprint. Đã quản lý ngân sách quảng cáo hơn 10 tỷ đồng.',
                'email' => 'leminhcuong@uia.test',
                'facebook' => 'https://facebook.com/leminhcuong',
                'linkedin' => null,
                'website' => 'https://leminhcuong.marketing',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Phạm Hoàng Dũng',
                'avatar' => null,
                'bio' => 'Mobile Developer chuyên Flutter & React Native. Đã phát triển hơn 50 ứng dụng trên App Store và Google Play.',
                'email' => 'phamhoangdung@uia.test',
                'facebook' => null,
                'linkedin' => 'https://linkedin.com/in/phamhoangdung',
                'website' => null,
                'is_active' => false, // Tạm ẩn để test filter
                'sort_order' => 4,
            ],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
