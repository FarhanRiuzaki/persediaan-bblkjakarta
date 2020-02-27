<?php
// src/Controller/Component/DatatablesComponent.php
namespace App\Controller\Component;

use Cake\Controller\Component;

class DatatablesComponent extends Component
{

	public function make($data)
	{
        $conf = [];
		$source  = $data['source'];
		$allData = $data['source'];
		$searchAble = $data['searchAble'];
		$defaultSort = ! empty( $data[ 'defaultSort' ]) ? $data[ 'defaultSort' ] : 'ASC';
		$defaultField = ! empty( $data[ 'defaultField' ]) ? $data[ 'defaultField' ] : '';
        $defaultSearch = ! empty( $data[ 'defaultSearch' ]) ? $data[ 'defaultSearch' ] : '';
        if(!empty($data['contain'])){
            $conf['contain'] = $data['contain'];
        }
		if(! empty($defaultSearch))
		{
            $defaultWhere = [];
			foreach ($defaultSearch as $key => $condition) {
                $defaultWhere[] = [
                    $condition['keyField'].' '.$condition['condition'] => $condition['value']
                ];
            }
            if(!empty($defaultWhere))
            {
                $conf['searchConds'] = $defaultWhere;
            }
		}
		$request = $this->request->data;
		$datatable = ! empty( $request ) ? $request : array();
		// pr($request);;
		$datatable = array_merge( array( 'pagination' => array(), 'sort' => array(), 'query' => array() ), $datatable );

		$sort  = $defaultSort;

		$meta    = array();


		$metaData = $source->find('all',$conf);

		if(!empty($datatable['order'])){
			foreach($datatable['order'] as $sd => $or){
				$col_index = $or['column'];
				if($datatable['columns'][$col_index]['orderable'] == "true"){
					$metaData->order([$datatable['columns'][$col_index]['name'] => $or['dir']]);
				}
			}
		}
		if($datatable['search']['value']) {
		//	dd($filter);
            $orWhere = []; 
            foreach ($datatable['columns'] as $key => $value) {
				if($value['searchable'] == "true"){
					$orWhere[] = [$value['name'].' LIKE' => '%'.$datatable['search']['value'].'%'];
				}
			}
			if(!empty($conf['searchConds'])){
				$metaData->where([
					$conf['searchConds'],
					'OR' => $orWhere
				]);
			}else{
				$metaData->orWhere($orWhere);
			}
		}
		if(!empty($data['having'])){
            $metaData = $metaData->having($data['having']);
        }
		if(!empty($data['fields'])){
			foreach($data['fields'] as $r){
				$metaData->select($r);
			}
		}
		$dataResult = $metaData;
		$dataCount = $metaData;

		$pages = 1;
		if(isset($data['union'])){
			$total = 0;
		}else{
			$total 	 = $dataCount->count();
		}

		$start    	= $datatable[ 'start' ];
		$length 	= $datatable[ 'length' ];
		$dataResult = $dataResult->limit($length)->offset($start);

		//dd($datatable);
		// $request = $this->request->all();
		// dd($request);		
		$meta = array(
		);
		//pr($conf);
		$result = array(
			'meta' => $meta + array(
					'sql' => $dataResult->sql()
				),
			'aaData' => $dataResult,
			'iTotalRecords' => $total,
			'iTotalDisplayRecords' => $total,
			'sColumns' => "",
			'sEcho' => 0,
        );
		return $result;

	}

	public function makes($data)
    {
        $conf = [];
        $source = $data['source'];
        $allData = $data['source'];
        $searchAble = $data['searchAble'];
        $defaultSort = !empty($data['defaultSort']) ? $data['defaultSort'] : 'ASC';
        $defaultField = !empty($data['defaultField']) ? $data['defaultField'] : '';
        $defaultSearch = !empty($data['defaultSearch']) ? $data['defaultSearch'] : '';
        if (!empty($data['contain'])) {
            $conf['contain'] = $data['contain'];
        }
        if (!empty($defaultSearch)) {
            $defaultWhere = [];
            foreach ($defaultSearch as $key => $condition) {
                $defaultWhere[] = [
                    $condition['keyField'] . ' ' . $condition['condition'] => $condition['value']
                ];
            }
            if (!empty($defaultWhere)) {
                $conf['conditions'] = $defaultWhere;
            }
        }
        $request = $this->request->data;
        $datatable = !empty($request['datatable']) ? $request['datatable'] : [];
        $datatable = array_merge(['pagination' => [], 'sort' => [], 'query' => []], $datatable);

        $filter = isset($datatable['query']['generalSearch']) && is_string($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        if (!empty($filter)) {
            //	dd($filter);
            $orWhere = [];
            foreach ($searchAble as $key => $value) {
                $orWhere[] = [$value . ' LIKE' => '%' . $filter . '%'];
            }
            if (!empty($orWhere)) {
                if (!empty($conf['conditions'])) {
                    $conf['conditions'] = $conf['conditions'] + ['OR' => $orWhere];
                } else {
                    $conf['conditions'] = ['OR' => $orWhere];
                }
            }
            unset($datatable['query']['generalSearch']);
        }

        $sort = !empty($datatable['sort']['sort']) ? $datatable['sort']['sort'] : $defaultSort;
        $field = !empty($datatable['sort']['field']) && $datatable['sort']['field'] != 'actions' ? $datatable['sort']['field'] : $defaultField;

        if (!empty($field)) {
            $conf['order'] = [$field => $sort];
        }

        $meta = [];
        $page = !empty($datatable['pagination']['page']) ? (int)$datatable['pagination']['page'] : 1;
        $perpage = !empty($datatable['pagination']['perpage']) ? (int)$datatable['pagination']['perpage'] : -1;

        $pages = 1;
        if (isset($data['union'])) {
            $total = 0;
        } else {
            $total = $source->find('all', $conf)->count();
        }
        // $perpage 0; get all data
        if ($perpage > 0) {
            $pages = ceil($total / $perpage); // calculate total pages
            $page = max($page, 1); // get 1 page when $_REQUEST['page'] <= 0
            $page = min($page, $pages); // get last page when $_REQUEST['page'] > $totalPages
            $offset = ($page - 1) * $perpage;
            if ($offset < 0) {
                $offset = 0;
            }

            // $data = array_slice( $data, $offset, $perpage, true );
            $conf['limit'] = $perpage;
            $conf['offset'] = $offset;
        }

        //dd($datatable);
        // $request = $this->request->all();
        // dd($request);
        $meta = [
            'page' => $page,
            'pages' => $pages,
            'perpage' => $perpage,
            'total' => $total,
        ];
        //pr($conf);
        $result = [
            'meta' => $meta + [
                'sort' => $sort,
                'field' => $field,
                'sql' => $source->find('all', $conf)->sql()
            ],
            'data' => $source->find('all', $conf),
        ];

        return $result;
    }

}

?>