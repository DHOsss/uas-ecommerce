<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ── Baju ───────────────────────────────────────────────────────────
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Kaos Essential Logo Outfitku',
                'description' => 'Kaos oversized dengan logo Outfitku di dada. Bahan cotton combed 30s yang adem dan nyaman.',
                'price'       => 179000,
                'stock'       => 50,
                'category'    => 'Baju',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Kaos Graphic Streetwear',
                'description' => 'Kaos dengan graphic print eksklusif. Cocok untuk tampilan street style sehari-hari.',
                'price'       => 199000,
                'stock'       => 40,
                'category'    => 'Baju',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Kaos Polos Premium Pique',
                'description' => 'Kaos polos berbahan premium pique cotton. Tersedia dalam berbagai pilihan warna.',
                'price'       => 149000,
                'stock'       => 60,
                'category'    => 'Baju',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL'],
                'name'        => 'Tank Top Performance Dry-Fit',
                'description' => 'Tank top olahraga bahan dry-fit yang menyerap keringat. Ideal untuk gym dan aktivitas outdoor.',
                'price'       => 159000,
                'stock'       => 35,
                'category'    => 'Baju',
            ],

            // ── Kemeja ─────────────────────────────────────────────────────────
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Kemeja Flannel Kotak Overshirt',
                'description' => 'Kemeja flannel motif kotak dengan potongan oversized. Cocok dipakai sebagai overshirt.',
                'price'       => 289000,
                'stock'       => 30,
                'category'    => 'Kemeja',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL'],
                'name'        => 'Kemeja Denim Slim Fit',
                'description' => 'Kemeja berbahan denim ringan dengan potongan slim fit. Tampilan kasual sekaligus stylish.',
                'price'       => 349000,
                'stock'       => 25,
                'category'    => 'Kemeja',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Kemeja Oxford Classic White',
                'description' => 'Kemeja oxford putih klasik. Bisa dipakai formal maupun casual, cocok untuk berbagai kesempatan.',
                'price'       => 319000,
                'stock'       => 35,
                'category'    => 'Kemeja',
            ],

            // ── Celana ─────────────────────────────────────────────────────────
            [
                'sizes'       => ['28', '30', '32', '34', '36'],
                'name'        => 'Celana Jogger Tech Series',
                'description' => 'Celana jogger dengan bahan tech fleece. Karet pinggang elastis dan kantong zippered.',
                'price'       => 249000,
                'stock'       => 45,
                'category'    => 'Celana',
            ],
            [
                'sizes'       => ['28', '30', '32', '34', '36'],
                'name'        => 'Celana Chino Urban Slim',
                'description' => 'Celana chino slim fit untuk tampilan smart casual. Bahan stretch yang nyaman dipakai seharian.',
                'price'       => 299000,
                'stock'       => 40,
                'category'    => 'Celana',
            ],
            [
                'sizes'       => ['28', '30', '32', '34', '36', '38'],
                'name'        => 'Celana Cargo Street Multi-Pocket',
                'description' => 'Celana cargo streetwear dengan banyak kantong. Desain tactical yang tetap stylish.',
                'price'       => 379000,
                'stock'       => 20,
                'category'    => 'Celana',
            ],

            // ── Jaket ──────────────────────────────────────────────────────────
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Jaket Bomber Varsity Edition',
                'description' => 'Jaket bomber gaya varsity dengan emblem Outfitku di lengan. Bahan twill premium yang tahan lama.',
                'price'       => 549000,
                'stock'       => 18,
                'category'    => 'Jaket',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'name'        => 'Jaket Hoodie Fleece Premium',
                'description' => 'Hoodie fleece tebal dengan kapasitas kantong depan jumbo. Hangat dan nyaman untuk aktivitas outdoor.',
                'price'       => 449000,
                'stock'       => 28,
                'category'    => 'Jaket',
            ],
            [
                'sizes'       => ['S', 'M', 'L', 'XL'],
                'name'        => 'Jaket Windbreaker Packable',
                'description' => 'Jaket windbreaker tipis yang bisa dilipat menjadi kantong sendiri. Anti angin dan tahan air ringan.',
                'price'       => 499000,
                'stock'       => 15,
                'category'    => 'Jaket',
            ],

            // ── Topi ───────────────────────────────────────────────────────────
            [
                'sizes'       => ['S/M', 'L/XL'],
                'name'        => 'Topi Snapback Classic Logo',
                'description' => 'Snapback dengan embossed logo Outfitku di depan. Adjuster kancing belakang yang dapat disesuaikan.',
                'price'       => 149000,
                'stock'       => 55,
                'category'    => 'Topi',
            ],
            [
                'sizes'       => ['S/M', 'L/XL'],
                'name'        => 'Topi Bucket Hat Reversible',
                'description' => 'Bucket hat dua sisi (reversible) dengan motif berbeda di setiap sisi. Bahan ripstop ringan.',
                'price'       => 169000,
                'stock'       => 42,
                'category'    => 'Topi',
            ],
            [
                'sizes'       => ['One Size'],
                'name'        => 'Topi Beanie Knit Ribbed',
                'description' => 'Beanie rajut dengan pola ribbed yang hangat dan trendi. Pas untuk cuaca dingin atau gaya kasual.',
                'price'       => 129000,
                'stock'       => 60,
                'category'    => 'Topi',
            ],

            // ── Sepatu ─────────────────────────────────────────────────────────
            [
                'sizes'       => ['38', '39', '40', '41', '42', '43', '44'],
                'name'        => 'Sneakers Runner X1 Outfitku',
                'description' => 'Sneakers lari dengan teknologi cushioning EVA. Ringan, responsif, dan desain modern yang eye-catching.',
                'price'       => 799000,
                'stock'       => 20,
                'category'    => 'Sepatu',
            ],
            [
                'sizes'       => ['38', '39', '40', '41', '42', '43', '44'],
                'name'        => 'Sepatu Canvas Low All-Day',
                'description' => 'Sepatu canvas low-cut klasik dengan sol karet vulcanized. Ringan dan cocok untuk pemakaian harian.',
                'price'       => 449000,
                'stock'       => 30,
                'category'    => 'Sepatu',
            ],
            [
                'sizes'       => ['38', '39', '40', '41', '42', '43'],
                'name'        => 'Sneakers High Top Street Pro',
                'description' => 'High top sneakers dengan upper kulit sintetis premium. Ankle support yang kuat untuk gaya street style.',
                'price'       => 699000,
                'stock'       => 5,
                'category'    => 'Sepatu',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product['name']], $product);
        }
    }
}
