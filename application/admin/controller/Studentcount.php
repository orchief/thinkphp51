<?php
// +----------------------------------------------------------------------
// | Description: 个人统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 21:18:08
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @route('admin/studentcount')
 */
class Studentcount extends Controller
{
    public $modelName = 'Studentcount';

    public function index()
    {
        $param = $this->params();
        $data = $this->model()->getDataList($param);

        // 类型是导出excel
        if(isset($param['outtype']) && $param['outtype'] == 'excel'){
            $this->excel($data['list']->toArray());
        };

        result($data, $this->model()->getError());
    }

    /**
     * 导出excel
     *
     * @return void
     */
    private function excel($datas)
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

        $worksheet = $spreadsheet->getActiveSheet();     //指向激活的工作表
        $worksheet->setTitle('模板测试标题');

        //向模板表中写入数据
        $worksheet->setCellValue('A2', '天津市气象局干部培训情况表（20XX年第X季度）');   //送入A1的内容
        $spreadsheet->getActiveSheet()->mergeCells('A2:N2');
        $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
            ->setWrapText(true); //设置自动换行
        $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->setName('宋体');
        $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->setSize(18)->setName('宋体')->setBold(true);

        $spreadsheet->getActiveSheet()->mergeCells('A3:N3');
        $worksheet->setCellValue('A3', '单位：XXXXX');

        $worksheet->setCellValue('A4', '学号')->getStyle('A4')->getFont()->setBold(true);
        $worksheet->setCellValue('B4', '姓名')->getStyle('B4')->getFont()->setBold(true);
        $worksheet->setCellValue('C4', '人员类别')->getStyle('C4')->getFont()->setBold(true);
        $worksheet->setCellValue('D4', '人员类型')->getStyle('D4')->getFont()->setBold(true);
        $worksheet->setCellValue('E4', '职务')->getStyle('E4')->getFont()->setBold(true);
        $worksheet->setCellValue('F4', '职务层次')->getStyle('F4')->getFont()->setBold(true);
        $worksheet->setCellValue('G4', '参加培训名称')->getStyle('G4')->getFont()->setBold(true);
        $worksheet->setCellValue('H4', '主办单位')->getStyle('H4')->getFont()->setBold(true);
        $worksheet->setCellValue('I4', '培训形式')->getStyle('I4')->getFont()->setBold(true);
        $worksheet->setCellValue('J4', '是否调训')->getStyle('J4')->getFont()->setBold(true);
        $worksheet->setCellValue('K4', '培训类型')->getStyle('K4')->getFont()->setBold(true);
        $worksheet->setCellValue('L4', '学时')->getStyle('L4')->getFont()->setBold(true);
        $worksheet->setCellValue('M4', '学制(天)')->getStyle('M4')->getFont()->setBold(true);
        $worksheet->setCellValue('N4', '培训费用(万元)')->getStyle('N4')->getFont()->setBold(true);

        $match = [
            [
                'student_id', 'A',
            ],
            [
                'username', 'B',
            ],
            [
                'category', 'C',
            ],
            [
                'type', 'D'
            ],
            [
                'position', 'E',
            ],
            [
                'rank', 'F'
            ],
            [
                'train_name', 'G'
            ],
            [
                'unit', 'H'
            ],
            [
                'modality', 'I'
            ],
            [
                'DiaoXun', 'J'
            ],
            [
                'train_cate_name', 'K'
            ],
            [
                'period', 'L'
            ],
            [
                'days', 'M'
            ],
            [
                'charge', 'N'
            ]
        ];

        for($i=65; $i<80; $i++){
            $arr[] = strtoupper(chr($i)); //输出大写字母
        }

        foreach($datas as $k => $v){
            // TODO 第一个是A
            foreach ($v as $kk => $item) {
                foreach($arr as $vv){
                    $m = $k + 5;
                    foreach($match as $vvv){
                        if($kk == $vvv[0] && $vv == $vvv[1]){
                            $worksheet->setCellValue($vv . $m, $item);
                        }
                    }
                }
            }
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(12);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        //下载文档
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. date('Y-m-d') .'_test'.'.xls"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;
}