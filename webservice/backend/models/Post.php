<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 14/01/17
 * Time: 12:44 PM
 */

namespace backend\models;
use common\models\Post as BasePost;
use common\models\Constants;
use common\models\PostCategory;
use common\models\Taxinomy;
use common\models\PostTaxinomy;
use yii\helpers\ArrayHelper;

class Post extends BasePost
{
    public $category;
    public $taxinomy;

    const SCENARIO_CREATEPOST = "post_create";
    const SCENARIO_UPDATEPOST = "post_update";

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[Post::SCENARIO_CREATEPOST] = ['title','type','user_id', 'slug', 'content','status','comment_allowed','category','taxinomy','featured_image'];
        $scenarios[Post::SCENARIO_UPDATEPOST] = ['title','type','user_id', 'slug', 'content','status','comment_allowed','category','taxinomy','featured_image'];
        return $scenarios;
    }


    public function rules()
    {
        return
        [
            ['type','default','value'=>Constants::TYPE_POST,'on'=>self::SCENARIO_CREATEPOST],
            ['type','default','value'=>Constants::TYPE_POST,'on'=>self::SCENARIO_UPDATEPOST],
            [['type', 'title', 'slug', 'user_id','content','category','status'], 'required'],
            [['content'], 'string'],
            ['category', 'each', 'rule' => ['integer']],
            [['views', 'comment_allowed', 'status', 'user_id', 'featured_image'], 'integer'],
            [['created_at', 'updated_at', 'featured_image'], 'safe'],
            [['type', 'title', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique']
        ];
    }

    public function doRegister()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try{
            $this->save(false);
            $this->refresh();

            $this->updateCategories($this);
            $this->updateTags($this);

            $transaction->commit();
        }

        catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    private function updateCategories($post_model)
    {
        PostCategory::deleteAll(['post_id'=>$post_model->id]);
        // Insert Post-Category Relation Mapping
        foreach ($post_model->category as $categoryid)
        {
            $postCategory = new PostCategory();
            $postCategory->post_id = $post_model->id;
            $postCategory->category_id = $categoryid;

            $postCategory->save();
        }
    }

    private function updateTags($post_model)
    {
        PostTaxinomy::deleteAll(['post_id'=>$post_model->id]);
        if ($post_model->taxinomy == '')
            return;

        //Post Tags
        $tags = explode(',', $post_model->taxinomy);
        $postTagId = [];

        if (is_array($tags) && sizeof($tags)>0)
        {
            array_walk($tags,'self::doTrim');
            $existingTags = ArrayHelper::map(Taxinomy::findAll(['type'=>Constants::TAXINOMY_TYPE_TAGS]), 'value', 'id');

            foreach ($tags as $tag)
            {
                if ($tag == '')
                    continue;

                if (in_array($tag,array_keys($existingTags)))
                {
                    $postTagId [] = $existingTags[$tag];
                }
                else{
                    $taxinomy = new Taxinomy();
                    $taxinomy->type = Constants::TAXINOMY_TYPE_TAGS;
                    $taxinomy->value = $tag;
                    if ($taxinomy->save() && $taxinomy->refresh())
                        $postTagId [] = $taxinomy->id;
                }
            }

            foreach ($postTagId as $id)
            {
                $post_tags = new PostTaxinomy();
                $post_tags->post_id = $post_model->id;
                $post_tags->taxinomy_id = $id;
                $post_tags->save();
            }
        }
    }

    public static function doTrim(&$item, $key)
    {
        $item = ucfirst(strtolower(trim($item)));
    }
}