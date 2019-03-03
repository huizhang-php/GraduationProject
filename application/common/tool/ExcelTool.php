<?php
/**
 * User: yuzhao
 * CreateTime: 2019/3/3 下午5:58
 * Description:
 */
namespace app\common\tool;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelTool {

    public function __construct()
    {

    }

    /**
     * User: yuzhao
     * CreateTime: 2019/3/3 下午6:01
     * @param $filePath
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * Description:
     */
    public static function getExcelContent($uploadfile) {
        $inputFileType = IOFactory::identify($uploadfile); //传入Excel路径
        $excelReader   = IOFactory::createReader($inputFileType); //Xlsx
        $PHPExcel      = $excelReader->load($uploadfile); // 载入excel文件
        $sheet         = $PHPExcel->getSheet(0); // 读取第一個工作表
        $sheetdata = $sheet->toArray();
        return $sheetdata; // --- 直接返回数组数据
    }
}