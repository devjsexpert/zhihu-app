<?php

namespace App\Http\Controllers;

use App\Repositories\AnswerRepository;
use App\Repositories\CommentRepository;
use App\Repositories\QuestionRepository;
use Auth;
use Illuminate\Http\Request;

/**
 * Class CommentsController
 * @package App\Http\Controllers
 */
class CommentsController extends Controller
{

    /**
     * @var AnswerRepository
     */
    protected $answer;
    /**
     * @var QuestionRepository
     */
    protected $question;
    /**
     * @var CommentRepository
     */
    protected $comment;

    /**
     * CommentsController constructor.
     * @param $answer
     * @param $question
     * @param $comment
     */
    public function __construct(AnswerRepository $answer, QuestionRepository $question, CommentRepository $comment)
    {
        $this->answer = $answer;
        $this->question = $question;
        $this->comment = $comment;
    }

    //答案的评论
    public function answer($id)
    {
        return $this->answer->getAnswerCommentsById($id);
    }

    //问题的评论
    public function question($id)
    {
        return $this->question->getQuestionCommentsById($id);
    }

    //用户评论
    public function store()
    {
        $model = $this->getModelNameFromType(request('type'));
        if(request('type')==='question'){
            $this->question->addCommentsCount(request('model'));
        }
        $this->answer->addCommentsCount(request('model'));
        return  $this->comment->create([
            'commentable_id'=>request('model'),
            'commentable_type'=>$model,
            'user_id'=>user('api')->id,
            'body'=>request('body'),
        ]);

    }

    //判断评论类型
    public function getModelNameFromType($type)
    {
        return $type === 'question' ? 'App\Question' : 'App\Answer';
    }
}
