<?php
class TreeRecordFileController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('admin', 'index', 'create', 'update'),
                'roles' => array('oprtn_createTreeRecordFile'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('oprtn_deleteTreeRecordFile'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     */
    public function actionCreate() {
        $model = new TreeRecordFile;

        if (isset($_POST['TreeRecordFile'])) {
            $model->attributes = $_POST['TreeRecordFile'];
            $model->fileName = CUploadedFile::getInstance($model, 'fileName');
            $pathInfo = pathinfo($model->fileName->getName());
            $model->name = $pathInfo['filename'];
            if ($model->save()) {
                // Extract all pages
                $pdftk = "/usr/bin/pdftk";

                // Find number of pages for this pdf
                $pdf_infos = array();
                $page_count = -1;
                exec($pdftk . ' ' . $model->fileName->getTempName() . ' dump_data', $pdf_infos);
                foreach ($pdf_infos as $pdf_info) {
                    $pdf_info_parts = explode(':', $pdf_info);
                    if ($pdf_info_parts[0] == 'NumberOfPages') {
                        $page_count = intval(trim($pdf_info_parts[1]));
                        break;
                    }
                }

                // Check if we found some pages
                if ($page_count > 0) {
                    // Create folder on file-system for individual pages
                    $treeRecordFolder = Yii::app()->basePath . '/uploads/treeRecords/' . $model->id . '/';
                    if (!is_dir($treeRecordFolder))
                        mkdir($treeRecordFolder);

                    // Now extract each page
                    for ($i = 1; $i <= $page_count; $i++) {
                        exec($pdftk . ' ' . $model->fileName->getTempName() . ' cat ' . $i . ' output ' . $treeRecordFolder . $i . '.pdf');

                        // Create new page record for extracted page
                        $treeRecordFilePage = new TreeRecordFilePage();
                        $treeRecordFilePage->tree_record_file_id = $model->id;
                        $treeRecordFilePage->page = $i;
                        $treeRecordFilePage->save();
                    }
                }

                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['TreeRecordFile'])) {
            $model->attributes = $_POST['TreeRecordFile'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $this->actionAdmin();
        
        /*$dataProvider = new CActiveDataProvider('TreeRecordFile');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));*/
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new TreeRecordFile('search');
        $model->unsetAttributes();  // clear any default values

        // check for new search parameters
        if (isset($_GET['TreeRecordFile'])) {
            $model->attributes = $_GET['TreeRecordFile'];
            Yii::app()->session['TreeRecordFile_filter'] = $_GET['TreeRecordFile'];
        }
        // if not try to retrieve from session
        else if( isset(Yii::app()->session['TreeRecordFile_filter']) ) {
            $model->attributes = Yii::app()->session['TreeRecordFile_filter'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = TreeRecordFile::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tree-record-file-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
