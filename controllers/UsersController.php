<?php

namespace app\controllers;

use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'index' => ['GET', 'HEAD'],
                        'create' => ['POST'],
                        'update' => ['PUT'],
                        'view' => ['GET'],
                        'delete' => ['DELETE'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Users models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $data = [];
        foreach ($dataProvider->getModels() as $key => $usuario) {
                $data[$key] = $usuario->attributes;
                $data[$key]['nombre'] = $usuario->nombre;
                $data[$key]['apellido'] = $usuario->apellido;
                $data[$key]['dni'] = $usuario->dni;
                $data[$key]['edad'] = $usuario->edad;
                $data[$key]['email'] = $usuario->email;
        }
        return array_merge($data, $this->serializePagination($dataProvider->getPagination()));

    }

    /**
     * Displays a single Users model.
     * @param int $id ID
     * @param int $dni DNI
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      
        return $this->findModel($id);
    
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Users();
        $request = Yii::$app->request->post();
        $model->load($request, '');

       if($request['edad'] < 18)
            return ['message' => 'La edad debe ser mayor o igual a 18.'];

        if($model->validate() && $model->save()) {
            return $model;
        }else{
            Yii::$app->getResponse()->setStatusCode(422);
            return $this->formaterErrores($model->errors);
        }

    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request->post();
        $model->load($request, '');
        if ($model->validate() && $model->save()) {
            return $model;
        }else{
            Yii::$app->getResponse()->setStatusCode(422);
            return $this->formaterErrores($model->errors);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete() === false) {
            Yii::$app->getResponse()->setStatusCode(425);
            return ['message' => 'No se pudo eliminar.'];
        }

        return ['message' => 'Eliminado correctamente.'];
        Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne(['dni' => $id])) !== null) {
            return $model;
        }else{
            return ['message' => 'Usuario no encontrado.'];
        }   

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function serializePagination($pagination)
    {
        return [
            '_links' => \yii\web\Link::serialize($pagination->getLinks(true)),
            '_meta' => [
                'totalCount' => $pagination->totalCount,
                'pageCount' => $pagination->getPageCount(),
                'currentPage' => $pagination->getPage() + 1,
                'perPage' => $pagination->getPageSize(),
            ],
        ];
    }

    protected function formaterErrores($errors)
    {
        $formato = [];
        foreach ($errors as $campo => $errores) {
            $field = [];
            $field['field'] = $campo;
            $field['errors'] = $errores[0];
            $formato[] = $field;
        }
        return $formato;
    }
}
