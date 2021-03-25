<?php
// +----------------------------------------------------------------------
// | Description: 个人总统计
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------
// | Date: 2021-03-05 21:50:35
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\AdminController as Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @route('admin/studenttotalcount')
 */
class Studenttotalcount extends Controller
{
    public $modelName = 'Studenttotalcount';
    public function index()
    {
        $param = $this->params();
        $data = $this->model()->getDataList($param);

        // 类型是导出excel
        if(isset($param['outtype']) && $param['outtype'] == 'excel'){
            $year = isset($param['year']) ? $param['year'] : year();
            $unit = isset($param['unit']) ? $param['unit'] : '天津市气象局';

            $this->excel($data['list']->toArray(), $unit, $year);
        };

        result($data, $this->model()->getError());
    }
    use \Rest\Read;
    use \Rest\Save;
    use \Rest\Update;
    use \Rest\Delete;

        /**
     * 导出excel
     *
     * @return void
     */
    private function excel($datas, $unit, $year)
    {
        // TODO 合计
        $sum = [];
        foreach($datas as $k => $v){
            foreach($v as $kk => $vv){
                $sum[$kk] = 0;
            }
        }
        foreach($datas as $k => $v){
            foreach($v as $kk => $vv){
                $sum[$kk] += (float)$vv;
            }
        }
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

        for($i=65; $i<80; $i++){
            $arr[] = strtoupper(chr($i)); //输出大写字母
        }

        //向模板表中写入数据
        $worksheet->setCellValue('A2', $unit . '干部培训情况表（' . $year . '年）');   //送入A1的内容
        $spreadsheet->getActiveSheet()->mergeCells('A2:N2');
        $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
            ->setWrapText(true); //设置自动换行
        $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->setName('宋体');
        $spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->setSize(18)->setName('宋体')->setBold(true);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(26);

        $spreadsheet->getActiveSheet()->mergeCells('H3:I3')->getStyle('H3:I3')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('J3:K3')->getStyle('J3:K3')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('L3:R3')->getStyle('L3:R3')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('S3:S4')->getStyle('S3:S4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('T3:T4')->getStyle('T3:T4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('U3:U4')->getStyle('U3:U4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('V3:V4')->getStyle('V3:V4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;

        $spreadsheet->getActiveSheet()->mergeCells('A3:A4')->getStyle('A3:A4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('B3:B4')->getStyle('B3:B4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('C3:C4')->getStyle('C3:C4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('D3:D4')->getStyle('D3:D4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;

        $spreadsheet->getActiveSheet()->mergeCells('E3:E4')->getStyle('E3:E4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('F3:F4')->getStyle('F3:F4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;
        $spreadsheet->getActiveSheet()->mergeCells('G3:G4')->getStyle('G3:G4')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);;

        $worksheet->setCellValue('A3', '学号')->getStyle('A3')->getFont()->setBold(true);
        $worksheet->setCellValue('B3', '姓名')->getStyle('B3')->getFont()->setBold(true);
        $worksheet->setCellValue('C3', '所在单位')->getStyle('C3')->getFont()->setBold(true);
        $worksheet->setCellValue('D3', '人员类别')->getStyle('D3')->getFont()->setBold(true);
        $worksheet->setCellValue('E3', '人员类型')->getStyle('E3')->getFont()->setBold(true);
        $worksheet->setCellValue('F3', '职务')->getStyle('F3')->getFont()->setBold(true);
        $worksheet->setCellValue('G3', '职务层次')->getStyle('G3')->getFont()->setBold(true);
        $worksheet->setCellValue('H3', '脱产培训')->getStyle('H3')->getFont()->setBold(true);
        $worksheet->setCellValue('H4', '学时')->getStyle('H4')->getFont()->setBold(true);
        $worksheet->setCellValue('I4', '学制（天）')->getStyle('I4')->getFont()->setBold(true);
        $worksheet->setCellValue('J4', '学时')->getStyle('J4')->getFont()->setBold(true);
        $worksheet->setCellValue('J3', '网络培训')->getStyle('J3')->getFont()->setBold(true);
        $worksheet->setCellValue('K4', '完成率')->getStyle('K4')->getFont()->setBold(true);
        $worksheet->setCellValue('L4', '党性教育')->getStyle('L4')->getFont()->setBold(true);
        $worksheet->setCellValue('M4', '专业能力')->getStyle('M4')->getFont()->setBold(true);
        $worksheet->setCellValue('N4', '系统教育')->getStyle('N4')->getFont()->setBold(true);
        $worksheet->setCellValue('O4', '新技术方法')->getStyle('O4')->getFont()->setBold(true);
        $worksheet->setCellValue('P4', '提高政治站位研修')->getStyle('P4')->getFont()->setBold(true);
        $worksheet->setCellValue('Q4', '气象基础知识')->getStyle('Q4')->getFont()->setBold(true);
        $worksheet->setCellValue('R4', '其他')->getStyle('R4')->getFont()->setBold(true);

        $worksheet->setCellValue('L3', '分类培训次数')->getStyle('L3')->getFont()->setBold(true);

        $worksheet->setCellValue('S3', '参加调训次数')->getStyle('S3')->getFont()->setBold(true);
        $worksheet->setCellValue('T3', '总学时')->getStyle('T3')->getFont()->setBold(true);
        $worksheet->setCellValue('U3', '总学制（天）')->getStyle('U3')->getFont()->setBold(true);
        $worksheet->setCellValue('V3', '总培训费用（万元）')->getStyle('V3')->getFont()->setBold(true);
        $match = [
            [
                'student_id', 'A',
            ],
            [
                'username', 'B',
            ],
            [
                'unit', 'C',
            ],
            [
                'category', 'D',
            ],
            [
                'type', 'E'
            ],
            [
                'position', 'F',
            ],
            [
                'rank', 'G'
            ],
            [
                'full_time_period', 'H'
            ],
            [
                'full_time_days', 'I'
            ],
            [
                'half_time_period', 'J'
            ],
            [
                'half_time_days', 'K'
            ],
            [
                'dangxing', 'L'
            ],
            [
                'zhuanye', 'M'
            ],
            [
                'xitong', 'N'
            ],
            [
                'jishu', 'O'
            ],
            [
                'tigao', 'P'
            ],            [
                'qixiang', 'Q'
            ],            [
                'qita', 'R'
            ],            [
                'totalnum', 'S'
            ],            [
                'period', 'T'
            ],
            [
                'days', 'U'
            ],
            [
                'charge', 'V'
            ]
        ];

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
        $total = ($m + 1);
        $worksheet->setCellValue('A' . ($m + 1), '合计');
        $spreadsheet->getActiveSheet()->mergeCells('A' . ($m + 1) . ':' . 'G' . ($m + 1))->getStyle('A' . ($m + 1) . ':' . 'G' . ($m + 1))->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);

        $fortotal = [
            [
                'full_time_period', 'H'
            ],
            [
                'full_time_days', 'I'
            ],
            [
                'half_time_period', 'J'
            ],
            [
                'half_time_days', 'K'
            ],
            [
                'dangxing', 'L'
            ],
            [
                'zhuanye', 'M'
            ],
            [
                'xitong', 'N'
            ],
            [
                'jishu', 'O'
            ],
            [
                'tigao', 'P'
            ],            [
                'qixiang', 'Q'
            ],            [
                'qita', 'R'
            ],            [
                'totalnum', 'S'
            ],            [
                'period', 'R'
            ],
            [
                'days', 'U'
            ],
            [
                'charge', 'V'
            ]
        ];

        foreach($fortotal as $v){
            $worksheet->setCellValue($v[1] . $total, $sum[$v[0]])->getStyle($v[1] . $total);
        }

        $worksheet->setCellValue('A' . ($m + 3), '年人均脱产培训学时：' . '100 学时');
        $spreadsheet->getActiveSheet()->mergeCells('A' . ($m + 3) . ':' . 'G' . ($m + 3))->getStyle('A' . ($m + 3) . ':' . 'G' . ($m + 3))->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);
        $worksheet->setCellValue('A' . ($m + 4), '年脱产培训调训率：' . '100 学时');
        $spreadsheet->getActiveSheet()->mergeCells('A' . ($m + 4) . ':' . 'G' . ($m + 4))->getStyle('A' . ($m + 4) . ':' . 'G' . ($m + 4))->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);
        $worksheet->setCellValue('A' . ($m + 5), '干部参训率：' . '100 学时');
        $spreadsheet->getActiveSheet()->mergeCells('A' . ($m + 5) . ':' . 'G' . ($m + 5))->getStyle('A' . ($m + 5) . ':' . 'G' . ($m + 5))->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) //设置垂直居中
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) //设置水平居中
        ->setWrapText(true);

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
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(12);

         $worksheet->setCellValue('V4', '总培训费用（万元）')->getStyle('N4')->getFont()->setBold(true);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        //下载文档
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. date('Y-m-d') .'_test'.'.xls"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}