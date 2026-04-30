<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();

        $cats = Category::where('company_id', $company->id)->get()->keyBy('name');
        foreach (['Alimentos', 'Bebidas', 'Limpieza', 'Cuidado Personal'] as $name) {
            if (!$cats->has($name)) {
                $cats[$name] = Category::create([
                    'company_id' => $company->id,
                    'name' => $name,
                    'is_active' => true,
                ]);
            }
        }

        $alimentos  = $cats['Alimentos']->id;
        $bebidas    = $cats['Bebidas']->id;
        $limpieza   = $cats['Limpieza']->id;
        $cuidado    = $cats['Cuidado Personal']->id;

        // [nombre, costo, venta, unidad, iva_type, stock]
        $templates = [
            // ALIMENTOS
            ['Arroz Typico 1kg',             4500,  6500, 'KG',     'EXENTO', 80],
            ['Arroz Typico 5kg',            21000, 29000, 'KG',     'EXENTO', 40],
            ['Arroz Premium 1kg',            6000,  9000, 'KG',     'EXENTO', 60],
            ['Arroz Premium 5kg',           28000, 38000, 'KG',     'EXENTO', 30],
            ['Fideo Tallarin 400g',          3500,  5000, 'UNIDAD', 'EXENTO', 100],
            ['Fideo Spaghetti 400g',         3500,  5000, 'UNIDAD', 'EXENTO', 100],
            ['Fideo Moño 400g',              3500,  5000, 'UNIDAD', 'EXENTO', 90],
            ['Fideo Codito 400g',            3500,  5000, 'UNIDAD', 'EXENTO', 90],
            ['Aceite Girasol 900ml',        12000, 17000, 'UNIDAD', 'IVA_10', 60],
            ['Aceite Girasol 1.8L',         22000, 31000, 'UNIDAD', 'IVA_10', 40],
            ['Aceite Soja 900ml',           11000, 16000, 'UNIDAD', 'IVA_10', 60],
            ['Aceite Soja 1.8L',            20000, 28000, 'UNIDAD', 'IVA_10', 35],
            ['Aceite de Oliva 500ml',       45000, 62000, 'UNIDAD', 'IVA_10', 20],
            ['Harina 0000 1kg',              4000,  6000, 'KG',     'EXENTO', 70],
            ['Harina 000 1kg',               3800,  5500, 'KG',     'EXENTO', 70],
            ['Harina Integral 1kg',          5000,  7000, 'KG',     'EXENTO', 50],
            ['Azucar Blanca 1kg',            4500,  6500, 'KG',     'EXENTO', 80],
            ['Azucar Rubia 1kg',             4000,  6000, 'KG',     'EXENTO', 60],
            ['Sal Fina 1kg',                 2000,  3000, 'KG',     'EXENTO', 90],
            ['Sal Gruesa 1kg',               1800,  2800, 'KG',     'EXENTO', 70],
            ['Yerba Mate 500g',              7000, 10000, 'UNIDAD', 'IVA_10', 80],
            ['Yerba Mate 1kg',              13000, 18000, 'UNIDAD', 'IVA_10', 60],
            ['Yerba Con Palo 500g',          6500,  9500, 'UNIDAD', 'IVA_10', 80],
            ['Yerba Sin Palo 500g',          7500, 11000, 'UNIDAD', 'IVA_10', 60],
            ['Atun en Lata 170g',            5500,  8000, 'UNIDAD', 'IVA_10', 120],
            ['Sardinas en Tomate 125g',      4000,  6000, 'UNIDAD', 'IVA_10', 100],
            ['Galletitas Maria 200g',        3000,  4500, 'UNIDAD', 'IVA_10', 100],
            ['Galletitas Chocolate 200g',    3800,  5500, 'UNIDAD', 'IVA_10', 80],
            ['Galletitas Soda 200g',         3200,  4800, 'UNIDAD', 'IVA_10', 80],
            ['Pan de Molde Blanco',          6500,  9500, 'UNIDAD', 'EXENTO', 40],
            ['Pan de Molde Integral',        7000, 10500, 'UNIDAD', 'EXENTO', 30],
            ['Mermelada Frutilla 454g',      9000, 13000, 'UNIDAD', 'IVA_10', 50],
            ['Mermelada Durazno 454g',       9000, 13000, 'UNIDAD', 'IVA_10', 50],
            ['Mermelada Uva 454g',           9000, 13000, 'UNIDAD', 'IVA_10', 40],
            ['Mayonesa 250g',                8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Mayonesa 500g',               14000, 20000, 'UNIDAD', 'IVA_10', 50],
            ['Ketchup 400g',                 7500, 11000, 'UNIDAD', 'IVA_10', 60],
            ['Mostaza 200g',                 5000,  7500, 'UNIDAD', 'IVA_10', 60],
            ['Salsa de Tomate 400g',         6000,  9000, 'UNIDAD', 'IVA_10', 70],
            ['Pure de Tomate 400g',          5500,  8000, 'UNIDAD', 'IVA_10', 70],
            ['Tomate Perita Lata 400g',      5000,  7500, 'UNIDAD', 'IVA_10', 60],
            ['Vinagre Blanco 1L',            3500,  5500, 'UNIDAD', 'IVA_10', 50],
            ['Cafe Molido 250g',            11000, 16000, 'UNIDAD', 'IVA_10', 50],
            ['Cafe Instantaneo 50g',         8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Te Negro x20 saq',             5000,  7500, 'UNIDAD', 'IVA_10', 60],
            ['Leche en Polvo 200g',          9000, 13000, 'UNIDAD', 'IVA_10', 50],
            ['Leche en Polvo 400g',         17000, 24000, 'UNIDAD', 'IVA_10', 40],
            ['Caldo de Gallina x12',         5500,  8000, 'UNIDAD', 'IVA_10', 80],
            ['Caldo de Carne x12',           5500,  8000, 'UNIDAD', 'IVA_10', 80],
            ['Pimienta Molida 50g',          4000,  6000, 'UNIDAD', 'IVA_10', 60],
            ['Oregano 20g',                  2500,  4000, 'UNIDAD', 'IVA_10', 70],
            ['Comino 50g',                   3000,  4500, 'UNIDAD', 'IVA_10', 60],
            ['Azucar Impalpable 500g',       5000,  7500, 'UNIDAD', 'IVA_10', 40],
            ['Polvo de Hornear 100g',        3500,  5500, 'UNIDAD', 'IVA_10', 50],
            ['Levadura Seca 11g',            2500,  4000, 'UNIDAD', 'IVA_10', 80],
            ['Maizena 250g',                 4500,  6500, 'UNIDAD', 'IVA_10', 60],
            ['Manteca 200g',                 9000, 13000, 'UNIDAD', 'IVA_10', 40],
            ['Margarina 200g',               7000, 10000, 'UNIDAD', 'IVA_10', 50],
            ['Crema de Leche 200ml',         6500,  9500, 'UNIDAD', 'IVA_10', 40],
            ['Dulce de Leche 400g',         10000, 15000, 'UNIDAD', 'IVA_10', 50],
            ['Chocolate en Polvo 200g',      8500, 13000, 'UNIDAD', 'IVA_10', 40],
            ['Gelatina Frutilla 20g',        1500,  2500, 'UNIDAD', 'IVA_10', 100],
            ['Gelatina Naranja 20g',         1500,  2500, 'UNIDAD', 'IVA_10', 100],
            ['Pudding Vainilla 100g',        3000,  4500, 'UNIDAD', 'IVA_10', 80],
            ['Popcorn Microondas 100g',      4500,  7000, 'UNIDAD', 'IVA_10', 60],
            ['Chipa Guazu Mix 500g',         7000, 10500, 'UNIDAD', 'IVA_10', 40],
            ['Mani Salado 200g',             4500,  7000, 'UNIDAD', 'IVA_10', 60],
            ['Chips Papas 100g',             5500,  8500, 'UNIDAD', 'IVA_10', 80],
            ['Chicle x10 unid',              2000,  3500, 'UNIDAD', 'IVA_10', 100],
            ['Caramelo Surtido 500g',        8000, 12000, 'UNIDAD', 'IVA_10', 50],
            ['Mermelada Mixed Berries 454g', 10000, 14500, 'UNIDAD', 'IVA_10', 30],
            ['Salsa Soja 150ml',             5000,  7500, 'UNIDAD', 'IVA_10', 50],
            ['Aceitunas Verdes 300g',        9000, 13500, 'UNIDAD', 'IVA_10', 40],
            ['Pickles Mix 300g',             8000, 12000, 'UNIDAD', 'IVA_10', 40],
            ['Miel 500g',                   18000, 26000, 'UNIDAD', 'IVA_10', 30],

            // BEBIDAS
            ['Coca Cola 2L',                 9000, 13000, 'UNIDAD', 'IVA_10', 80],
            ['Coca Cola 1.5L',               7000, 10500, 'UNIDAD', 'IVA_10', 80],
            ['Coca Cola 600ml',              4500,  7000, 'UNIDAD', 'IVA_10', 100],
            ['Coca Cola 354ml lata',         5000,  7500, 'UNIDAD', 'IVA_10', 100],
            ['Pepsi 2L',                     8500, 12500, 'UNIDAD', 'IVA_10', 70],
            ['Pepsi 600ml',                  4000,  6500, 'UNIDAD', 'IVA_10', 90],
            ['Sprite 2L',                    8500, 12500, 'UNIDAD', 'IVA_10', 70],
            ['Sprite 600ml',                 4000,  6500, 'UNIDAD', 'IVA_10', 90],
            ['Fanta Naranja 2L',             8500, 12500, 'UNIDAD', 'IVA_10', 60],
            ['Fanta Naranja 600ml',          4000,  6500, 'UNIDAD', 'IVA_10', 80],
            ['Agua Mineral 500ml',           2500,  4000, 'UNIDAD', 'EXENTO', 120],
            ['Agua Mineral 1.5L',            4000,  6000, 'UNIDAD', 'EXENTO', 100],
            ['Agua Mineral 5L',             10000, 14000, 'UNIDAD', 'EXENTO', 40],
            ['Agua Soda 500ml',              3000,  5000, 'UNIDAD', 'IVA_10', 80],
            ['Jugo Naranja 1L',              6000,  9500, 'UNIDAD', 'IVA_10', 60],
            ['Jugo Manzana 1L',              6000,  9500, 'UNIDAD', 'IVA_10', 60],
            ['Jugo Durazno 1L',              6000,  9500, 'UNIDAD', 'IVA_10', 60],
            ['Jugo Multifrutas 1L',          6000,  9500, 'UNIDAD', 'IVA_10', 60],
            ['Cerveza Pilsen 1L',            8500, 12500, 'UNIDAD', 'IVA_10', 80],
            ['Cerveza Pilsen 600ml',         5500,  8500, 'UNIDAD', 'IVA_10', 100],
            ['Cerveza Pilsen Lata 350ml',    5000,  7500, 'UNIDAD', 'IVA_10', 80],
            ['Cerveza Imperial 600ml',       6000,  9000, 'UNIDAD', 'IVA_10', 80],
            ['Cerveza Brahma 600ml',         5800,  8800, 'UNIDAD', 'IVA_10', 80],
            ['Vino Tinto 750ml',            18000, 26000, 'UNIDAD', 'IVA_10', 40],
            ['Vino Blanco 750ml',           18000, 26000, 'UNIDAD', 'IVA_10', 40],
            ['Vino Rose 750ml',             18000, 26000, 'UNIDAD', 'IVA_10', 30],
            ['Sidra 750ml',                 15000, 22000, 'UNIDAD', 'IVA_10', 30],
            ['Whisky Botella 750ml',        95000,140000, 'UNIDAD', 'IVA_10', 20],
            ['Caña Blanca 750ml',           22000, 32000, 'UNIDAD', 'IVA_10', 30],
            ['Gin 750ml',                   75000,110000, 'UNIDAD', 'IVA_10', 20],
            ['Energizante 250ml',            7500, 11000, 'UNIDAD', 'IVA_10', 60],
            ['Energizante 500ml',           12000, 17000, 'UNIDAD', 'IVA_10', 50],
            ['Leche Entera 1L',              5500,  8500, 'UNIDAD', 'EXENTO', 60],
            ['Leche Descremada 1L',          6000,  9000, 'UNIDAD', 'EXENTO', 50],
            ['Leche Saborizada Chocolate 1L',6500,  9500, 'UNIDAD', 'IVA_10', 50],
            ['Yogurt Natural 200g',          4500,  7000, 'UNIDAD', 'IVA_10', 50],
            ['Yogurt Frutilla 200g',         4500,  7000, 'UNIDAD', 'IVA_10', 50],
            ['Yogurt Durazno 200g',          4500,  7000, 'UNIDAD', 'IVA_10', 50],
            ['Yogurt Bebible 900ml',        11000, 16000, 'UNIDAD', 'IVA_10', 40],
            ['Refresco Naranja Polvo 35g',   2500,  4000, 'UNIDAD', 'IVA_10', 100],
            ['Refresco Uva Polvo 35g',       2500,  4000, 'UNIDAD', 'IVA_10', 100],
            ['Refresco Lima Limon Polvo 35g',2500,  4000, 'UNIDAD', 'IVA_10', 100],
            ['Isotonica Naranja 500ml',      7000, 11000, 'UNIDAD', 'IVA_10', 60],
            ['Isotonica Limon 500ml',        7000, 11000, 'UNIDAD', 'IVA_10', 60],
            ['Terere Listo 500ml',           4000,  6500, 'UNIDAD', 'IVA_10', 80],
            ['Mate Cocido x25 saq',          6000,  9000, 'UNIDAD', 'IVA_10', 60],
            ['Jugo Toronja 1L',              6000,  9500, 'UNIDAD', 'IVA_10', 40],
            ['Tonica 350ml',                 5000,  7500, 'UNIDAD', 'IVA_10', 40],
            ['Ron Blanco 750ml',            55000, 80000, 'UNIDAD', 'IVA_10', 20],
            ['Vodka 750ml',                 65000, 95000, 'UNIDAD', 'IVA_10', 20],
            ['Campari 750ml',               80000,115000, 'UNIDAD', 'IVA_10', 15],
            ['Amaro 750ml',                 70000,100000, 'UNIDAD', 'IVA_10', 15],
            ['Aperol 750ml',                85000,125000, 'UNIDAD', 'IVA_10', 15],
            ['Cerveza Artesanal IPA 500ml', 16000, 24000, 'UNIDAD', 'IVA_10', 30],
            ['Cerveza Artesanal Rubia 500ml',15000, 22000, 'UNIDAD', 'IVA_10', 30],
            ['Jugo Camu Camu 500ml',        12000, 18000, 'UNIDAD', 'IVA_10', 25],
            ['Kombucha Natural 350ml',      10000, 16000, 'UNIDAD', 'IVA_10', 20],
            ['Leche de Almendra 1L',        18000, 26000, 'UNIDAD', 'EXENTO', 20],
            ['Leche de Soja 1L',            12000, 18000, 'UNIDAD', 'EXENTO', 25],
            ['Agua Tonica 350ml',            4500,  7000, 'UNIDAD', 'IVA_10', 50],
            ['Coctel de Frutas Lata 500ml',  6500, 10000, 'UNIDAD', 'IVA_10', 50],
            ['Jugo de Limon 500ml',          7000, 10500, 'UNIDAD', 'IVA_10', 40],
            ['Gaseosa Limón 2L',             8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Gaseosa Pomelo 2L',            8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Cerveza Sin Alcohol 330ml',    6000,  9000, 'UNIDAD', 'IVA_10', 30],
            ['Cava Brut 750ml',             35000, 52000, 'UNIDAD', 'IVA_10', 20],
            ['Champagne 750ml',             60000, 88000, 'UNIDAD', 'IVA_10', 15],
            ['Licor de Naranja 750ml',      50000, 73000, 'UNIDAD', 'IVA_10', 15],
            ['Licor de Cafe 750ml',         48000, 70000, 'UNIDAD', 'IVA_10', 15],
            ['Anisado 750ml',               40000, 58000, 'UNIDAD', 'IVA_10', 15],
            ['Jugo Verde Detox 300ml',      12000, 18000, 'UNIDAD', 'IVA_10', 20],
            ['Agua Infusionada Pepino 500ml', 5000,  8000, 'UNIDAD', 'EXENTO', 30],
            ['Cafe Listo Frio 250ml',        9000, 14000, 'UNIDAD', 'IVA_10', 40],

            // LIMPIEZA
            ['Detergente Liquido 1L',       12000, 18000, 'UNIDAD', 'IVA_10', 60],
            ['Detergente Liquido 500ml',     7000, 11000, 'UNIDAD', 'IVA_10', 80],
            ['Detergente en Polvo 500g',     9000, 14000, 'UNIDAD', 'IVA_10', 60],
            ['Detergente en Polvo 1kg',     16000, 24000, 'UNIDAD', 'IVA_10', 50],
            ['Detergente en Polvo 3kg',     42000, 62000, 'UNIDAD', 'IVA_10', 30],
            ['Jabon en Barra 200g',          3500,  5500, 'UNIDAD', 'IVA_10', 100],
            ['Jabon Liquido Manos 250ml',    8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Jabon Liquido Manos 500ml',   13000, 19000, 'UNIDAD', 'IVA_10', 50],
            ['Lavandina 1L',                 5500,  8500, 'UNIDAD', 'IVA_10', 80],
            ['Lavandina 2L',                 9500, 14000, 'UNIDAD', 'IVA_10', 60],
            ['Desinfectante Pino 1L',        8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Desinfectante Lavanda 1L',     8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Limpiapisos Limon 1L',         7500, 11500, 'UNIDAD', 'IVA_10', 60],
            ['Limpiapisos Pino 1L',          7500, 11500, 'UNIDAD', 'IVA_10', 60],
            ['Limpiavidrios 500ml',          7000, 10500, 'UNIDAD', 'IVA_10', 50],
            ['Quitagrasa Cocina 500ml',      9000, 14000, 'UNIDAD', 'IVA_10', 40],
            ['Desengrasante 500ml',          9500, 14500, 'UNIDAD', 'IVA_10', 40],
            ['Suavizante Ropa 1L',          10000, 15000, 'UNIDAD', 'IVA_10', 60],
            ['Suavizante Ropa 500ml',        6500, 10000, 'UNIDAD', 'IVA_10', 60],
            ['Papel Higienico x4 rollos',    8500, 13000, 'PAQUETE','EXENTO', 80],
            ['Papel Higienico x8 rollos',   15000, 22000, 'PAQUETE','EXENTO', 60],
            ['Papel Higienico x12 rollos',  20000, 29000, 'PAQUETE','EXENTO', 40],
            ['Servilleta x50',               4000,  6000, 'PAQUETE','EXENTO', 80],
            ['Papel Toalla x2 rollos',       7000, 11000, 'PAQUETE','EXENTO', 60],
            ['Bolsas Basura 60L x10',        6000,  9000, 'PAQUETE','IVA_10', 80],
            ['Bolsas Basura 100L x5',        5500,  8500, 'PAQUETE','IVA_10', 70],
            ['Bolsas Chango x50',            4500,  7000, 'PAQUETE','IVA_10', 100],
            ['Film Transparente 30m',        8000, 12000, 'UNIDAD', 'IVA_10', 40],
            ['Papel Aluminio 30m',           9000, 14000, 'UNIDAD', 'IVA_10', 40],
            ['Escoba Plastica',             18000, 27000, 'UNIDAD', 'IVA_10', 20],
            ['Mopa Trapero',                22000, 32000, 'UNIDAD', 'IVA_10', 20],
            ['Pala Plastica',                8000, 13000, 'UNIDAD', 'IVA_10', 20],
            ['Guantes Goma M',               7000, 11000, 'PAR',    'IVA_10', 40],
            ['Esponja Doble Faz x2',         3500,  5500, 'PAQUETE','IVA_10', 80],
            ['Virulana x2',                  4000,  6500, 'PAQUETE','IVA_10', 80],
            ['Pastilla Odorizante Inodoro',  5000,  7500, 'UNIDAD', 'IVA_10', 60],
            ['Limpiador Baño 750ml',        10000, 15000, 'UNIDAD', 'IVA_10', 50],
            ['Gel Desinfectante 500ml',     12000, 18000, 'UNIDAD', 'IVA_10', 40],
            ['Alcohol 96° 500ml',           10000, 15000, 'UNIDAD', 'IVA_10', 50],
            ['Repelente Insectos Aerosol',  18000, 27000, 'UNIDAD', 'IVA_10', 30],
            ['Insecticida Aerosol 400ml',   20000, 30000, 'UNIDAD', 'IVA_10', 30],
            ['Cera Piso 500ml',             13000, 19000, 'UNIDAD', 'IVA_10', 30],
            ['Brillo Muebles 300ml',        12000, 18000, 'UNIDAD', 'IVA_10', 30],
            ['Limpia Horno 400ml',          15000, 22000, 'UNIDAD', 'IVA_10', 25],
            ['Suavizante Concentrado 500ml',14000, 21000, 'UNIDAD', 'IVA_10', 40],
            ['Jabon Polvo Bebé 500g',       11000, 17000, 'UNIDAD', 'IVA_10', 30],
            ['Balde 10L',                   22000, 33000, 'UNIDAD', 'IVA_10', 15],
            ['Trapo de Piso x2',             7000, 11000, 'PAQUETE','IVA_10', 30],
            ['Pinza Ropa x12',               4500,  7000, 'PAQUETE','IVA_10', 40],
            ['Lavaplatos en Crema 500g',     8000, 12000, 'UNIDAD', 'IVA_10', 50],
            ['Lavaplatos Liquido 500ml',     7000, 11000, 'UNIDAD', 'IVA_10', 60],
            ['Quitamanchas Spray 500ml',    11000, 17000, 'UNIDAD', 'IVA_10', 40],
            ['Almidón Aerosol 400ml',       12000, 18000, 'UNIDAD', 'IVA_10', 30],
            ['Plumero Polvo',               10000, 15000, 'UNIDAD', 'IVA_10', 20],
            ['Secador Piso',                15000, 22000, 'UNIDAD', 'IVA_10', 20],
            ['Detergente Lavavajilla Auto 1kg', 20000, 30000, 'UNIDAD', 'IVA_10', 25],
            ['Pastilla Lavavajilla Auto x25',   22000, 33000, 'PAQUETE','IVA_10', 20],
            ['Limpia Microondas 300ml',     11000, 16000, 'UNIDAD', 'IVA_10', 20],
            ['Desodorizante Ambiente 400ml',14000, 21000, 'UNIDAD', 'IVA_10', 40],
            ['Bolsas Ziplock x20',           5500,  8500, 'PAQUETE','IVA_10', 50],

            // CUIDADO PERSONAL
            ['Shampoo Clasico 400ml',       14000, 21000, 'UNIDAD', 'IVA_10', 60],
            ['Shampoo Anti-Caspa 400ml',    16000, 24000, 'UNIDAD', 'IVA_10', 60],
            ['Shampoo Hidratante 400ml',    16000, 24000, 'UNIDAD', 'IVA_10', 60],
            ['Shampoo 2en1 400ml',          15000, 22000, 'UNIDAD', 'IVA_10', 60],
            ['Acondicionador Clasico 400ml',15000, 22000, 'UNIDAD', 'IVA_10', 50],
            ['Acondicionador Reparador 400ml',16000, 24000, 'UNIDAD', 'IVA_10', 50],
            ['Crema para Peinar 200ml',     12000, 18000, 'UNIDAD', 'IVA_10', 40],
            ['Jabon de Baño 90g',            3500,  5500, 'UNIDAD', 'IVA_10', 100],
            ['Jabon Liquido Ducha 250ml',   10000, 15000, 'UNIDAD', 'IVA_10', 60],
            ['Jabon Liquido Ducha 500ml',   16000, 24000, 'UNIDAD', 'IVA_10', 50],
            ['Desodorante Aerosol Hombre 150ml', 14000, 21000, 'UNIDAD', 'IVA_10', 60],
            ['Desodorante Aerosol Mujer 150ml',  14000, 21000, 'UNIDAD', 'IVA_10', 60],
            ['Desodorante Roll-on 50ml',    10000, 15000, 'UNIDAD', 'IVA_10', 60],
            ['Desodorante Stick 50g',       11000, 17000, 'UNIDAD', 'IVA_10', 50],
            ['Pasta Dental 90g',             7000, 11000, 'UNIDAD', 'IVA_10', 80],
            ['Pasta Dental Blanqueadora 90g',9000, 13500, 'UNIDAD', 'IVA_10', 60],
            ['Cepillo Dental Adulto',        5500,  8500, 'UNIDAD', 'IVA_10', 80],
            ['Cepillo Dental Niño',          5000,  7500, 'UNIDAD', 'IVA_10', 60],
            ['Hilo Dental 50m',              5000,  8000, 'UNIDAD', 'IVA_10', 60],
            ['Enjuague Bucal 500ml',        14000, 21000, 'UNIDAD', 'IVA_10', 40],
            ['Afeitadora Descartable x2',    7000, 11000, 'PAQUETE','IVA_10', 60],
            ['Espuma de Afeitar 200ml',     10000, 15000, 'UNIDAD', 'IVA_10', 50],
            ['Crema de Manos 100ml',         8000, 12000, 'UNIDAD', 'IVA_10', 60],
            ['Crema Corporal 400ml',        18000, 27000, 'UNIDAD', 'IVA_10', 40],
            ['Locion Corporal 400ml',       20000, 30000, 'UNIDAD', 'IVA_10', 40],
            ['Protector Solar FPS50 200ml', 35000, 52000, 'UNIDAD', 'IVA_10', 30],
            ['Protector Solar FPS30 200ml', 28000, 42000, 'UNIDAD', 'IVA_10', 30],
            ['Toallas Femeninas x10',       10000, 15000, 'PAQUETE','EXENTO', 60],
            ['Toallas Femeninas x20',       18000, 27000, 'PAQUETE','EXENTO', 50],
            ['Tampones x8',                  9000, 14000, 'PAQUETE','EXENTO', 50],
            ['Pañales Bebe T1 x24',         35000, 52000, 'PAQUETE','EXENTO', 30],
            ['Pañales Bebe T2 x22',         35000, 52000, 'PAQUETE','EXENTO', 30],
            ['Pañales Bebe T3 x20',         35000, 52000, 'PAQUETE','EXENTO', 30],
            ['Pañales Bebe T4 x18',         35000, 52000, 'PAQUETE','EXENTO', 25],
            ['Toallitas Humedas Bebe x60',  12000, 18000, 'PAQUETE','EXENTO', 40],
            ['Algodon 100g',                 5000,  7500, 'UNIDAD', 'IVA_10', 50],
            ['Hisopos x100',                 4500,  7000, 'UNIDAD', 'IVA_10', 60],
            ['Maquinilla Depilar Desech x2', 8000, 12000, 'PAQUETE','IVA_10', 40],
            ['Crema Depilatoria 100ml',     18000, 27000, 'UNIDAD', 'IVA_10', 30],
            ['Gel Cabello Fuerte 250ml',    12000, 18000, 'UNIDAD', 'IVA_10', 40],
            ['Gomina Cabello 100ml',        10000, 15000, 'UNIDAD', 'IVA_10', 40],
            ['Tinte Cabello Rubio',         28000, 42000, 'UNIDAD', 'IVA_10', 20],
            ['Tinte Cabello Castaño',       28000, 42000, 'UNIDAD', 'IVA_10', 20],
            ['Tinte Cabello Negro',         28000, 42000, 'UNIDAD', 'IVA_10', 20],
            ['Mascarilla Capilar 200ml',    20000, 30000, 'UNIDAD', 'IVA_10', 30],
            ['Serum Capilar 100ml',         25000, 37000, 'UNIDAD', 'IVA_10', 25],
            ['Perfume Colonia 100ml',       45000, 68000, 'UNIDAD', 'IVA_10', 20],
            ['Perfume Colonia 50ml',        28000, 42000, 'UNIDAD', 'IVA_10', 25],
            ['Agua de Colonia 250ml',       22000, 33000, 'UNIDAD', 'IVA_10', 20],
            ['Repelente Piel 100ml',        18000, 27000, 'UNIDAD', 'IVA_10', 30],
            ['Talco Bebe 200g',             10000, 15000, 'UNIDAD', 'IVA_10', 40],
            ['Crema Solar Facial FPS50',    32000, 48000, 'UNIDAD', 'IVA_10', 20],
            ['Labial Hidratante',            8000, 12000, 'UNIDAD', 'IVA_10', 40],
            ['Limpiador Facial 150ml',      22000, 33000, 'UNIDAD', 'IVA_10', 30],
            ['Tonico Facial 150ml',         25000, 37000, 'UNIDAD', 'IVA_10', 25],
            ['Crema Facial Hidratante 50ml',30000, 45000, 'UNIDAD', 'IVA_10', 20],
            ['Desmaquillante 200ml',        18000, 27000, 'UNIDAD', 'IVA_10', 25],
            ['Rimel Mascara Pestañas',      22000, 33000, 'UNIDAD', 'IVA_10', 20],
            ['Base Maquillaje Liquida',     35000, 52000, 'UNIDAD', 'IVA_10', 15],
        ];

        $categoryMap = [
            'Alimentos'       => $alimentos,
            'Bebidas'         => $bebidas,
            'Limpieza'        => $limpieza,
            'Cuidado Personal'=> $cuidado,
        ];

        // Rangos de índice por categoría para asignar category_id
        $categoryRanges = [
            [0,   75,  $alimentos],
            [75,  150, $bebidas],
            [150, 225, $limpieza],
            [225, 300, $cuidado],
        ];

        $prefixes = ['ALM', 'BEB', 'LMP', 'CPE'];
        $skipped = 0;
        $created = 0;

        foreach ($templates as $i => $t) {
            [$name, $cost, $sale, $unit, $iva, $stock] = $t;

            // Determinar categoría y prefijo de código por posición
            foreach ($categoryRanges as [$from, $to, $catId]) {
                if ($i >= $from && $i < $to) {
                    $categoryId = $catId;
                    $prefixIdx  = array_search([$from, $to, $catId], $categoryRanges);
                    break;
                }
            }

            $prefix    = $prefixes[intdiv($i, 75)];
            $localNum  = ($i % 75) + 1;
            $code      = $prefix . str_pad($localNum, 3, '0', STR_PAD_LEFT);

            // Evitar duplicados si el seeder se corre más de una vez
            $exists = Product::where('company_id', $company->id)
                             ->where('code', $code)
                             ->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            Product::create([
                'company_id'  => $company->id,
                'category_id' => $categoryId,
                'code'        => $code,
                'name'        => $name,
                'cost_price'  => $cost,
                'sale_price'  => $sale,
                'unit'        => $unit,
                'iva_type'    => $iva,
                'track_stock' => true,
                'stock'       => $stock,
                'min_stock'   => (int)($stock * 0.2),
                'is_active'   => true,
            ]);

            $created++;
        }

        $this->command->info("Productos creados: {$created} | Omitidos (ya existían): {$skipped}");
    }
}
