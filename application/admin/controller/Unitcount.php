<?php
// +----------------------------------------------------------------------
// | Description: 单位统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 12:59:15
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * @route('admin/unitcount')
 */
class Unitcount extends Controller
{
    public $modelName = 'Unitcount';
    use \Rest\Index;

    /**
     * 导出excel
     *
     * @return void
     */
    public function excel()
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator("FastAdmin")
            ->setLastModifiedBy("FastAdmin")
            ->setTitle("标题")
            ->setSubject("Subject");
        $spreadsheet->getDefaultStyle()->getFont()->setName('Microsoft Yahei');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);

        $worksheet = $spreadsheet->setActiveSheetIndex(0);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../天1津市气象局干部培训.xls');  //读取模板
        $worksheet = $spreadsheet->getActiveSheet();     //指向激活的工作表
        $worksheet->setTitle('模板测试标题');

        //向模板表中写入数据
        $worksheet->setCellValue('A1', '模板测试内容');   //送入A1的内容
        // $worksheet->getCell('B2')->setValue($result['rows'][$i]['week']);    //星期
        // $worksheet->getCell('d2')->setValue($result['rows'][$i]['genderdata']);  //性别
        // $worksheet->getCell('f2')->setValue($result['rows'][$i]['hobbydata']);  //爱好
        // $worksheet->getCell('b3')->setValue($result['rows'][$i]['title']);  //标题
        // $worksheet->getCell('b4')->setValue($result['rows'][$i]['content']);  //内容

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        //下载文档
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. date('Y-m-d') .'_test'.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}