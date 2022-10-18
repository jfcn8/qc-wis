<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;
use App\Models\Supplier;
use App\Models\Unit;

class ArticleClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'supplier' => 'PS DBM'
        ]);

        $classification_ = Classification::create(['classification' => 'Janitorial Supplies']);
        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Dust Mop Set'
        ]);

        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Toilet Tissue Paper'
        ]);

        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Stick Broom (Walis ting-ting)'
        ]);

        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Dust Pan'
        ]);
        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Concentrated Powder'
        ]);
        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Air freshener'
        ]);

        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Mop Head'
        ]);

        Article::create([
            'classification_id' => $classification_->classification_id,
            'article' => 'Rag'
        ]);

        Classification::create(['classification' => 'Consumable Supplies']);

        $office = Classification::create(['classification' => 'Office Supplies']);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Cartolina'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Correction Tape'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Cutter Blade'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Envelope'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Fastener'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Folder'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Scissor'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Glue'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Ink for Stamp pad'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Stapler'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Permanent Marker'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Rubber Band'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Binder Clip'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Paper Clip'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Letter Paper 8.5x11'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Legal Paper - 8.5x14'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Legal Paper - 8.5x13'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'A4 Paper'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Staples'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Ruler'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Correction Tape'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Stamp pad'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Note pad'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Pad paper'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Pen'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Puncher'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Record Book'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Stamp Pad felt'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Tape'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Staple Remover'
        ]);
        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Alcohol'
        ]);


        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Flash Drive'
        ]);

        Article::create([
            'classification_id' => $office->classification_id,
            'article' => 'Scouring Pad'
        ]);

        $classification_ = Classification::create(['classification' => 'Others']);


        $unit_ = [
            

            [
                'mnemonic' =>'BG',
                'unit' => 'Bag'
            ],
            [
                'mnemonic' =>'LT',
                'unit' => 'Lot'
            ],
            [
                'mnemonic' =>'LY',
                'unit' => 'Linear Yard'
            ],
            [
                'mnemonic' =>'BL',
                'unit' => 'Bale'
            ],
            [
                'mnemonic' =>'BT',
                'unit' => 'Bottle'
            ],
            [
                'mnemonic' =>'MG',
                'unit' => 'Milligram'
            ],
            [
                'mnemonic' =>'BX',
                'unit' => 'Box'
            ],
            [
                'mnemonic' =>'ML',
                'unit' => 'Milliliter'
            ],
            [
                'mnemonic' =>'MM',
                'unit' => 'Millimeter'
            ],
            [
                'mnemonic' =>'CC',
                'unit' => 'Cubic Centimeter'
            ],
            [
                'mnemonic' =>'CF',
                'unit' => 'Cubic Feet'
            ],
            [
                'mnemonic' =>'MR',
                'unit' => 'Micron'
            ],
            [
                'mnemonic' =>'MT',
                'unit' => 'Meter'
            ],
            [
                'mnemonic' =>'CM',
                'unit' => 'Centimeter'
            ],
            [
                'mnemonic' =>'CN',
                'unit' => 'Can'
            ],
            [
                'mnemonic' =>'OZ',
                'unit' => 'Ounce'
            ],
            [
                'mnemonic' =>'CS',
                'unit' => 'Case'
            ],
            [
                'mnemonic' =>'PA',
                'unit' => 'Package'
            ],
            [
                'mnemonic' =>'CT',
                'unit' => 'Carton'
            ],
            [
                'mnemonic' =>'PC',
                'unit' => 'Piece'
            ],
            [
                'mnemonic' =>'PG',
                'unit' => 'Page'
            ],
            [
                'mnemonic' =>'CY',
                'unit' => 'Cubic Yard'
            ],
            [
                'mnemonic' =>'PK',
                'unit' => 'Pack'
            ],
            [
                'mnemonic' =>'DI',
                'unit' => 'Diameter'
            ],
            [
                'mnemonic' =>'PL',
                'unit' => 'Pail'
            ],
            [
                'mnemonic' =>'DR',
                'unit' => 'Drum'
            ],
            [
                'mnemonic' =>'PR',
                'unit' => 'Pair'
            ],
            [
                'mnemonic' =>'PT',
                'unit' => 'Pint'
            ],
            [
                'mnemonic' =>'QR',
                'unit' => 'Quarter'
            ],
            [
                'mnemonic' =>'DZ',
                'unit' => 'Dozen'
            ],
            [
                'mnemonic' =>'QT',
                'unit' => 'Quart'
            ],
            [
                'mnemonic' =>'EA',
                'unit' => 'Each'
            ],
            [
                'mnemonic' =>'FT',
                'unit' => 'Feet'
            ],
            [
                'mnemonic' =>'RL',
                'unit' => 'Roll'
            ],
            [
                'mnemonic' =>'GL',
                'unit' => 'Gallon'
            ],
            [
                'mnemonic' =>'RM',
                'unit' => 'Ream'
            ],
            [
                'mnemonic' =>'GM',
                'unit' => 'Gram'
            ],
            [
                'mnemonic' =>'SF',
                'unit' => 'Square Feet'
            ],
            [
                'mnemonic' =>'SH',
                'unit' => 'Sheet'
            ],
            [
                'mnemonic' =>'ST',
                'unit' => 'Set'
            ],
            [
                'mnemonic' =>'SY',
                'unit' => 'Square Yard'
            ],
            [
                'mnemonic' =>'IN',
                'unit' => 'Inch'
            ],
            [
                'mnemonic' =>'TB',
                'unit' => 'Tube'
            ],
            [
                'mnemonic' =>'JR',
                'unit' => 'Jar'
            ],
            [
                'mnemonic' =>'KG',
                'unit' => 'Kilogram'
            ],
            [
                'mnemonic' =>'UT',
                'unit' => 'Unit'
            ],
            [
                'mnemonic' =>'KT',
                'unit' => 'Kit'
            ],
            [
                'mnemonic' =>'VL',
                'unit' => 'Vial'
            ],
            [
                'mnemonic' =>'LB',
                'unit' => 'Pound'
            ],
            [
                'mnemonic' =>'YD',
                'unit' => 'Yard'
            ],
            [
                'mnemonic' =>'LI',
                'unit' => 'Liter'
            ],
            
        ];

        Unit::insert($unit_);
    }
}
