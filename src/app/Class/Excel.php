<?php

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Excel
{
    /**
     * __construct
     */
    public function __construct()
    {
    }

    public function bindExcel($datas = [])
    {
        // スプレッドシート作成
        $spreadsheet = new Spreadsheet();
        // フォントサイズを設定
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $spreadsheet->getDefaultStyle()->getFont()->setName('游ゴシック');
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        /**
         * 
         * 
         *  表示の作成
         * 
         * 
         */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getDefaultRowDimension()->setRowHeight(25);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(75);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->setTitle('表紙');

        $sheet->mergeCells('B2:E16');
        $sheet->getStyle('B2:E16')->getBorders()->getAllBorders()->setBorderStyle('thin');
        $sheet->setCellValue('B2', 'DB定義書');
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $font = $sheet->getStyle('B2')->getFont();
        $font->setSize(28);

        $headerCell = [
            'DB', 'NAME', 'VERSION', '更新日'
        ];

        $sheet->fromArray($headerCell, NULL, 'B18', true);
        $sheet->getStyle('B18:E18')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B18:E18')->getFont()->setBold(true);
        $sheet->getStyle('B18:E18')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('B18:E18')->getFont()->setSize(10);
        // 背景色の設定
        $fill = $sheet->getStyle('B18:E18')->getFill();
        $fill->setFillType(Fill::FILL_SOLID);
        $fill->getStartColor()->setRGB('1d4170');

        $sheet->getStyle('B18:E19')->getBorders()->getAllBorders()->setBorderStyle('thin');

        $sheet->setCellValue('B19', $datas['database']);
        $sheet->setCellValue('C19', $datas['title']);
        $sheet->setCellValue('D19', $datas['version']);
        $sheet->setCellValue('E19', '');
        $sheet->getStyle('B18:E19')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('B20:E21');
        $sheet->getStyle('B20:E21')->getBorders()->getAllBorders()->setBorderStyle('thin');

        $sheet->setCellValue('B20', $datas['comment']);
        $sheet->getStyle('B20')->getAlignment()->setIndent(1);
        $sheet->getStyle('B20')->getAlignment()->setWrapText(true);
        $sheet->getStyle('B20')->getFont()->setSize(10);

        /**
         * 
         * 
         *  エンティティ一覧作成
         * 
         * 
         */
        $page = 1;
        /** 追加 */
        $spreadsheet->createSheet($page);
        $spreadsheet->setActiveSheetIndex($page);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getDefaultRowDimension()->setRowHeight(25);
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(75);
        $sheet->setTitle('エンティティ一覧');
        $sheet->setCellValue('A1', '# エンティティ一覧');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $headerCell = [
            'NO', '論理名', '物理名', '定義情報', 'Comment'
        ];
        $sheet->fromArray($headerCell, NULL, 'A2', true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:C2')->getAlignment()->setIndent(1);
        $sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2')->getAlignment()->setIndent(1);
        $sheet->getStyle('A2:E2')->getFont()->setBold(true);
        $sheet->getStyle('A2:E2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A2:E2')->getFont()->setSize(10);
        // 背景色の設定
        $fill = $sheet->getStyle('A2:E2')->getFill();
        $fill->setFillType(Fill::FILL_SOLID);
        $fill->getStartColor()->setRGB('1d4170');

        $sheet->getStyle('A2:E4')->getBorders()->getAllBorders()->setBorderStyle('thin');
        $sheet->getStyle('A2:E4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

        $i = 3;
        $lastIndex = count($datas['tables']);

        foreach ($datas['tables'] as $name => $table) {
            $no = $i - 2;
            $rowStyle = $sheet->getStyle('A' . $i . ':E' . $i);
            $rowStyle->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOTTED);
            $rowStyle->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
            $rowStyle->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
            $rowStyle->getBorders()->getVertical()->setBorderStyle(Border::BORDER_THIN);
            if ($no === $lastIndex) {
                $rowStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
            } else {
                $rowStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
            }
            $sheet->setCellValue('A' . $i, $no);
            $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue('B' . $i, $table['name']);
            $sheet->setCellValue('C' . $i, $table['table']);
            $sheet->getStyle('B' . $i . ':C' . $i)->getAlignment()->setShrinkToFit(true);
            $sheet->getStyle('B' . $i . ':C' . $i)->getAlignment()->setIndent(1);
            $sheet->setCellValue('D' . $i, $table['definition']);
            $sheet->getStyle('D' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            /** 改行を反映 */
            $comment = str_replace("\r\n", PHP_EOL, $table['comment']);
            $sheet->setCellValue('E' . $i, $comment);
            $sheet->getStyle('E' . $i)->getAlignment()->setWrapText(true);
            
            /** 行高さ調整 */
            $lineCount = substr_count($comment, PHP_EOL) + 1;
            $lineHeight = 25;
            $sheet->getRowDimension($i)->setRowHeight($lineCount * $lineHeight);


            /* 特定行の背景の色をセット */
            if (!empty($table['bgcolor'])) {
                $style = $sheet->getStyle('A' . $i . ':E' . $i);
                $fill = $style->getFill();
                $fill->setFillType(Fill::FILL_SOLID);
                $fill->getStartColor()->setRGB(str_replace('#', '', $table['bgcolor']));
            }

            $i++;
        }

        $page++;

        /**
         * 
         * 
         *  テーブル定義作成
         * 
         * 
         */

        $titles = [];

        foreach ($datas['tables'] as $name => $table) {

            /** 追加 */
            $spreadsheet->createSheet($page);
            $spreadsheet->setActiveSheetIndex($page);

            $sheet = $spreadsheet->getActiveSheet();

            // シート名を設定
            $title = (empty($table['name'])) ? $table['table'] : $table['name'];

            if (isset($titles[$title])) {
                $titles[$title]++;
                $title .= ' (' . $titles[$title] . ')';
            } else {
                $titles[$title] = 1;
            }

            $sheet->setTitle(mb_substr($title, 0, 31));

            // 高さ・幅を設定
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(5);
            $sheet->getColumnDimension('F')->setWidth(5);
            $sheet->getColumnDimension('G')->setWidth(10);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(40);

            $sheet->setCellValue('A1', '# テーブル情報');
            $sheet->getStyle('A1')->getFont()->setBold(true);

            // リンク
            $sheet->setCellValue('I1', '# エンティティ一覧 #');
            $sheet->getCell('I1')
            ->getHyperlink()
            ->setUrl("sheet://'エンティティ一覧'!A1");
            $sheet->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $font = $sheet->getStyle('I1')->getFont();
            $font->getcolor()->setARGB('000000FF');
            $font->setUnderline('single');
            $font->setSize(11);

            // テーブル情報

            $headerCell = [
                'NO', '論理名', '物理名', 'Comment'
            ];
            $sheet->mergeCells('D2:I2');
            $sheet->fromArray($headerCell, NULL, 'A2', true);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B2:D2')->getAlignment()->setIndent(1);
            $sheet->getStyle('A2:D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:D2')->getFont()->setBold(true);
            $sheet->getStyle('A2:D2')->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A2:D2')->getFont()->setSize(10);
            // 背景色の設定
            $fill = $sheet->getStyle('A2:D2')->getFill();
            $fill->setFillType(Fill::FILL_SOLID);
            $fill->getStartColor()->setRGB('1d4170');

            $sheet->mergeCells('A3:A4');
            $sheet->mergeCells('B3:B4');
            $sheet->mergeCells('C3:C4');
            $sheet->mergeCells('D3:I4');

            $sheet->setCellValue('A3', $page);
            $sheet->setCellValue('B3', $table['name']);
            $sheet->setCellValue('C3', $table['table']);
            $sheet->setCellValue('D3', $table['comment']);
            $sheet->getStyle('A3:C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3:C3')->getAlignment()->setShrinkToFit(true);
            $sheet->getStyle('D3')->getAlignment()->setWrapText(true);
            $sheet->getStyle('A2:I4')->getBorders()->getAllBorders()->setBorderStyle('thin');


            $sheet->setCellValue('A6', '# フィールド情報');
            $sheet->getStyle('A6')->getFont()->setBold(true);

            $i = 7;
            /* header */
            $headerCell = [
                'NO', '論理名', '物理名', '型', 'KEY', 'Null', 'Default', 'Extra', 'Comment'
            ];
            $sheet->fromArray($headerCell, NULL, 'A' . $i, true);
            $objStyle = $sheet->getStyle('A' . $i . ':I' . $i);
            $objStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objStyle->getFont()->setBold(true);
            $objStyle->getFont()->getColor()->setRGB('FFFFFF');
            $objStyle->getFont()->setSize(10);

            $objStyle->getBorders()->getAllBorders()->setBorderStyle('thin');
            $objStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
            // 背景色の設定
            $fill = $objStyle->getFill();
            $fill->setFillType(Fill::FILL_SOLID);
            $fill->getStartColor()->setRGB('1d4170');

            $i++;
            $lastIndex = count($table['columns']) - 1;

            foreach ($table['columns'] as $no => $item) {
                $rowStyle = $sheet->getStyle('A' . $i . ':I' . $i);
                $rowStyle->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOTTED);
                $rowStyle->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                $rowStyle->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                $rowStyle->getBorders()->getVertical()->setBorderStyle(Border::BORDER_THIN);

                if ($no === $lastIndex) {
                    $rowStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                } else {
                    $rowStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                }

                $sheet->setCellValue('A' . $i, (int)$no + 1);
                $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('B' . $i, $item['name']);
                $sheet->setCellValue('C' . $i, $item['field']);
                $sheet->getStyle('B' . $i . ':C' . $i)->getAlignment()->setShrinkToFit(true);
                $sheet->getStyle('B' . $i . ':C' . $i)->getAlignment()->setIndent(1);
                $sheet->setCellValue('D' . $i, $item['type']);
                $sheet->setCellValue('E' . $i, $item['key']);
                //$sheet->setCellValue('G' . $i, $item['null']);
                $sheet->setCellValue('F' . $i, ($item['null'] == 'YES') ? '' : '×');
                $sheet->setCellValue('G' . $i, $item['default']);
                $sheet->setCellValue('H' . $i, $item['extra']);
                $sheet->getStyle('D' . $i . ':H' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /** 改行を反映 */
                $comment = str_replace("\r\n", PHP_EOL, $item['comment']);
                $sheet->setCellValue('I' . $i, $comment);
                $sheet->getStyle('I' . $i)->getAlignment()->setWrapText(true);

                /**行高さ調整 */
                $lineCount = substr_count($comment, PHP_EOL) + 1;
                $lineHeight = 25;
                $sheet->getRowDimension($i)->setRowHeight($lineCount * $lineHeight);


                /* 特定行の背景の色をセット */
                if (!empty($item['bgcolor'])) {
                    $style = $sheet->getStyle('A' . $i . ':I' . $i);
                    $fill = $style->getFill();
                    $fill->setFillType(Fill::FILL_SOLID);
                    $fill->getStartColor()->setRGB(str_replace('#', '', $item['bgcolor']));
                }

                $i++;
            }

            $i++;

            $sheet->setCellValue('A' . $i, '# インデックス情報');
            $sheet->getStyle('A' . $i)->getFont()->setBold(true);

            $i++;
            /* header */
            $headerCell = [
                'NO', 'キー名', 'フィールド', '主キー', '一意'
            ];
            $sheet->mergeCells('E' . $i .':G' . $i);
            $sheet->mergeCells('H' . $i .':I' . $i);
            $sheet->fromArray($headerCell, NULL, 'A' . $i, true);
            $sheet->setCellValue('H' . $i, 'Comment');

            $objStyle = $sheet->getStyle('A' . $i . ':I' . $i);
            $objStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $objStyle->getFont()->setBold(true);
            $objStyle->getFont()->getColor()->setRGB('FFFFFF');
            $objStyle->getFont()->setSize(10);

            $objStyle->getBorders()->getAllBorders()->setBorderStyle('thin');
            $objStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
            // 背景色の設定
            $fill = $objStyle->getFill();
            $fill->setFillType(Fill::FILL_SOLID);
            $fill->getStartColor()->setRGB('1d4170');



            $i++;
            $lastIndex = count($table['indexs']) - 1;

            foreach ($table['indexs'] as $no => $item) {
                $sheet->mergeCells('E' . $i .':G' . $i);
                $sheet->mergeCells('H' . $i .':I' . $i);
                $rowStyle = $sheet->getStyle('A' . $i . ':I' . $i);
                $rowStyle->getBorders()->getTop()->setBorderStyle(Border::BORDER_DOTTED);
                $rowStyle->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
                $rowStyle->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
                $rowStyle->getBorders()->getVertical()->setBorderStyle(Border::BORDER_THIN);

                if ($no === $lastIndex) {
                    $rowStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                } else {
                    $rowStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                }

                $sheet->setCellValue('A' . $i, (int)$no + 1);
                $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('B' . $i, $item['name']);
                $sheet->setCellValue('C' . $i, $item['column']);
                $sheet->getStyle('B' . $i . ':C' . $i)->getAlignment()->setShrinkToFit(true);
                $sheet->getStyle('B' . $i . ':C' . $i)->getAlignment()->setIndent(1);

                $sheet->setCellValue('D' . $i, ($item['primary']) ? '○' : '');
                $sheet->setCellValue('E' . $i, ($item['unique']) ? '○' : '');
                $sheet->setCellValue('H' . $i, $item['comment']);
                $sheet->getStyle('D' . $i . ':E' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $i++;
            }
            $page++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        // // // Excelファイル書き出し
        // $writer = new Xlsx($spreadsheet);
        // $writer->save('score.xlsx');

        $day = new \DateTime();
        $filename = 'database_' . $day->format('YmdGis') . '.xlsx';

        // //ブラウザへの指定
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename={$filename}");
        header('Cache-Control: max-age=0');

        $objWriter = new Xlsx($spreadsheet);
        $objWriter->save('php://output'); 
        exit;
    }
}
