<?php
namespace App\Shell;

use Cake\Console\Shell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

/**
 * ImportAccounts shell command.
 */
class ImportProductsShell extends Shell
{


    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('SubCategories');
    }
    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        // $this->out($this->OptionParser->help());
        $location = "C:\Users\User\Documents\EXPORT_SHELL_PERSEDIAAN_JAKARTA\products.xlsx";
        $spreadsheet = IOFactory::load($location);
        $worksheet = $spreadsheet->getActiveSheet();
        $dataExcel = [];
        foreach ($worksheet->getRowIterator() as $key =>  $row) {
            $dataExcel[$key] =[];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            foreach ($cellIterator as $keycel => $cell) {
                $dataExcel[$key][$keycel] = $cell->getValue();
            }
        }
        // dd($dataExcel);
        // $this->out($dataExcel);
        foreach($dataExcel as $key => $row){
            $code_sub           = trim($row['A']);
            $code               = trim($row['C']);
            $name               = trim($row['D']);
            $unit               = trim($row['E']);

            $category = $this->SubCategories->find('all', [
                'conditions' => [
                    'SubCategories.code' => $code_sub
                ]
            ])->first();

            // dump($category);
            $save = $this->Products->newEntity([
                'sub_category_id' => $category->id,
                'code' => $code,
                'name' => $name,
                'unit' => $unit,
                'status' => 1,
            ],[
                'validate'=>false
            ]);
            $this->Products->save($save);
        }
    }
}
