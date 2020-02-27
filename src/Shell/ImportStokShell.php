<?php
namespace App\Shell;

use Cake\Console\Shell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

/**
 * ImportAccounts shell command.
 */
class ImportStokShell extends Shell
{


    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('InitStocksDetails');
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
        $location = "C:\Users\User\Documents\EXPORT_SHELL_PERSEDIAAN_JAKARTA\stok.xlsx";
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
            $this->out("CODE : ". $row['A']. " - ". $row['B']. " - ". $row['C']. " - ". $row['E']);
            $code_sub           = trim($row['A']);
            $qty                = trim($row['C']);
            $price              = trim($row['D']);
            $exp                = trim($row['E']);
            // dd($qty);

            $category = $this->Products->find('all', [
                'conditions' => [
                    'Products.code' => $code_sub
                ]
            ])->first();

            // dump($category);
            $save = $this->InitStocksDetails->newEntity([
                'init_stock_id' => '1',
                'product_id'    => $category->id,
                'qty'           => $qty,
                'price'         => $price,
                'expired'       => $exp,
                'status'        => 1,
            ],[
                'validate'=>false
            ]);
            $this->InitStocksDetails->save($save);
        }
    }
}
