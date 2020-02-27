<?php
namespace App\Shell;

use Cake\Console\Shell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

/**
 * ImportAccounts shell command.
 */
class ImportSubShell extends Shell
{


    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Categories');
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
        $location = "C:\Users\User\Documents\EXPORT_SHELL_PERSEDIAAN_JAKARTA\sub.xlsx";
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
            $code_category  = trim($row['A']);
            $name_category  = trim($row['B']);
            $code_sub       = trim($row['C']);
            $name_sub       = trim($row['D']);
            $status = 1;

            $category = $this->Categories->find('all', [
                'conditions' => [
                    'Categories.code' => $code_category
                ]
            ])->first();

            // dd($category->id);
            $save = $this->SubCategories->newEntity([
                'category_id' => $category->id,
                'code' => $code_sub,
                'name' => $name_sub,
                'status' => 1,
            ],[
                'validate'=>false
            ]);
            $this->SubCategories->save($save);
        }
    }
}
