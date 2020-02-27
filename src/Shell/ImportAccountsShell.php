<?php
namespace App\Shell;

use Cake\Console\Shell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

/**
 * ImportAccounts shell command.
 */
class ImportAccountsShell extends Shell
{


    public function initialize()
    {
        parent::initialize();
        $this->loadModel('CodeAccounts');
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
        $location = "/Users/ardiansyahiqbal/Desktop/akun.xlsx";
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
        foreach($dataExcel as $key => $row){
            $this->out("CODE : ". $row['A']. " - ". $row['B']);
            $code = trim($row['A']);
            $name = trim($row['B']);
            $status = 1;
            $parent_id = substr($code,0,1);
            $normal_balance = 0;
            if($parent_id == 1 || $parent_id == 3 || $parent_id == 5){
                $normal_balance =0;
            }else{
                $normal_balance = 1;
            }
            $save = $this->CodeAccounts->newEntity([
                'parent_id' => $parent_id,
                'code' => $code,
                'name' => $name,
                'status' => 1,
                'normal_balance' => $normal_balance
            ],[
                'validate'=>false
            ]);
            $this->CodeAccounts->save($save);
        }
    }
}
