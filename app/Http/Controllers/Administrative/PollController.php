<?php

namespace App\Http\Controllers\Administrative;
use App\Http\Controllers\Controller;

use App\Poll;
use App\Answer;
use App\AnswersSurveys;
use App\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{

    private $msgSuccess = "Encuesta creada correctamente";
    private $msgError = "Error procesando la solicitud realizada";
    private $msgUpdated = "Encuesta actualizada correctamente";
    private $msgDeleted = "Encuesta eliminada correctamente"; 
    private $msgDeletedQuestion = "Pregunta eliminada correctamente";
    private $msgDeletedAnswer = "Respuesta eliminada correctamente";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('poll.index');
    }

    public function list() {
        $polls = Poll::with(['questions'])
            ->orderBy('id', 'desc')
            ->get();
        $list = view('poll.sub.list', compact('polls'))->render();

        return response(['success' => true, 'view' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $poll= new Poll();
        return view('poll.create',compact('poll'));
    }

    public function addQuestionRow(Request $request){
        $question    = null;
        $key       = uniqid();
        $questionId = $request->input('pollId');
        $row       = view('poll.forms.frames.question_new_row',compact('question','key','questionId'))->render();
        return response(['success' => true, 'row' => $row, 'key'=>$key]);
    }


    public function addAnswerRow(Request $request){
        $answer    = null;
        $skey      = uniqid();
        $key       = $request->input('key');
        $row       = view('poll.forms.frames.answer_new_row',compact('answer','key','skey'))->render();
        return response(['success' => true, 'row' => $row, 'key'=>$key, 'skey'=>$skey]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $poll = new Poll();
        $success = $this->save($request,$poll);

        return response(["success" => $success,  "message" => $success ? $this->msgSuccess : $this->msgError]);
    }

    private function save(Request $request, Poll $poll){

        // $this->validateForm($request, $poll);
       
        try {
            DB::beginTransaction();

            //si la encuesta va ser activada, se desactivan las demas primero
            if( $request->filled('enabled')){
                $otherPolls = Poll::where('id', '!=', $poll->id)->get();
                foreach ($otherPolls as $op){
                    $op->update(["enabled" => 0]);
                }              
            }

            $poll->title = $request->input('title');
            $poll->observation   = $request->input('observation');
            $poll->enabled = $request->filled('enabled') ? 1: 0;
            $poll->save();

            foreach ($request->input('question') as $qkey => $question) {
                
                $question = new Request($question);
                
                if(!is_null($question->input('question'))){

                    if($question->input('id')){

                        $questions = Question::find($question->input('id'));
                        $questions->question = $question->input('question');
                        $questions->save();
                    }else{
                        
                        $questions = $poll->questions()->create(
                            [
                            'question'=>$question['question'],
                            ]

                        );
                    }
                }
    
                foreach ($question->input('answer') as $akey => $answer) {
                
                    $answer = new Request($answer);
                    
                    if(!is_null($answer->input('answer'))){
                        if($answer->input('id')){
                            $answers = Answer::find($answer->input('id'));
                            $answers->answer = $answer->input('answer');
                            $answers->save();
                        }else{
                            $questions->answers()->create(
                                [
                                'answer'=>$answer['answer'],
                                ]
    
                            );
                        }
                    } 
                }
            }

            

            DB::commit();
            return true;
        } catch (Exception $exc) {
            dd($exc->getMessage());
            DB::rollback();
            return false;
        }
    }

    public function storeAnswer(Request $request){

        $this->validateFormSurvay($request);
       
        try {
            DB::beginTransaction();

            $answerSurvey = AnswersSurveys::create([
                'headquarter_id'    => $request->headquarter_id,
                'office_id'         => $request->office_id,
                'crm_schedule_id'   => $request->crm_schedule_id,
                'polls_id'          => $request->polls_id,
                'user_id'           => $request->user_id
            ]);

            foreach ($request->input('questions') as $qkey => $question) {
                
                $requestt = new Request($question);

                $answerSurvey->detailsAnswer()->create([
                    'question_id'   => $requestt->question_id,
                    'answer_id'     => $requestt->answer_id
                ]);
                
            }
            DB::commit();
            return response(["success" => true,  "message" => "Encuesta guardada correctamente"]);
        } catch (Exception $exc) {
            DB::rollback();
            return response(["success" => true,  "message" => "Error al guardar la encuesta", "error" => $exc]);
        }
    }

    private function validateFormSurvay(Request $request) {

        $niceNames = [
            'headquarter_id'            => 'Sede',
            'office_id'                 => 'Sucursal',
            'crm_schedule_id'           => 'Crm Schedule',
            'polls_id'                  => 'Encuesta',
            'user_id'                   => 'Usuario',

            'questions.*.question_id'   => 'Pregunta',
            'questions.*.answer_id'     => 'Respuesta'
        ];
        
        $request->validate([
            'headquarter_id'            => 'required|exists:headquarters,id',
            'office_id'                 => 'required|exists:offices,id',
            'crm_schedule_id'           => 'required|exists:crm_schedules,id',
            'polls_id'                  => 'required|exists:polls,id',
            'user_id'                   => 'required|exists:users,id',
            'questions.*.question_id'   => 'required|exists:questions,id',
            'questions.*.answer_id'     => 'required|exists:answers,id'
        ], 
        [], $niceNames);
            
    }

    
    public function listResult(Request $request)
    {
        $object=AnswersSurveys::headquarter();
        $term = '';
            if ($request->filled('search')) {
                $term = $request->input('search');
                
                $object->where(function ($w) use ($term) {
                    $w->whereHas('poll', function ($u) use ($term) {
                        $u->where('title', 'LIKE', "%$term%");
                    });
                });
                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('office', function ($u) use ($term) {
                        $u->where('name', 'LIKE', "%$term%");
                    });
                });
                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('office', function ($u) use ($term) {
                        $u->whereHas('crm', function ($us) use ($term) {
                            $us->where('name', 'LIKE', "%$term%");
                            $us->orWhere('commercial_name', 'LIKE', "%$term%");
                        });
                    });
                });
                
                $object->orWhere(function ($w) use ($term) {
                    $w->whereHas('user', function ($u) use ($term) {
                        $u->where('name', 'LIKE', "%$term%");
                    });
                });

            }
        $answersSurveys=$object->headquarter()->orderBy('id', 'desc')->paginate($request->filled('perpage') ? 
        $request->input('perpage') : 10);
        if($request->ajax()){
            $view = view('poll.sub.listResults',compact('answersSurveys'))->render();
            return response(['success' => true, 'view' => $view]);
        }
        return view('poll.surveysAnswers',compact('answersSurveys'));
    }
    public function resultSurvay(Request $request)
    {
        $answerSurvey=AnswersSurveys::headquarter()->find($request->id);
        $view       = view('poll.forms.showResultSurvey',compact('answerSurvey'))->render();
        return response(['success' => true, 'view' => $view]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Poll  $poll
     * @return \Illuminate\Http\Response
     */
    public function edit($poll)
    {
        $poll = Poll::with(['questions.answers'])->find($poll);
        
        return view('poll.create',compact('poll'));
    }

    public function update(Request $request)
    {
        $id        = $request->input('id');
        $poll       = Poll::find($id);
        $success   = $this->save($request, $poll);

        return response(["success" => $success, "message" => $success ? $this->msgUpdated : $this->msgError]);
    }

    public function deleteForm(Request $request){
        $id            = $request->input('id');
        $poll          = Poll::find($id);
        $form          = view('poll.forms.deleteForm', compact('poll'))->render();

        return response(['success' => true, 'view' => $form]);
    }
    
    public function softDelete(Request $request)
    {
        $id     = $request->input('id');
        $note   = Poll::destroy($id);
        
        return response(["success" => 'true', "message" => $note ? $this->msgDeleted : $this->msgError]); 
    }
    public function softDeleteSurvey(Request $request)
    {
        $value   = AnswersSurveys::destroy($request->id);
        return response(["success" => 'true', "message" => $value ? $this->msgDeleted : $this->msgError]); 
    }


    public function deleteQuestionForm(Request $request){
        $id           = $request->input('id');
        $question     = Question::find($id);
        $form         = view('poll.forms.deleteFormQuestion', compact('question'))->render();

        return response(['success' => $question? true: false, 'view' => $form]);
    }

    public function deleteQuestion(Request $request){
        $id = $request->input('id');
        $success = Question::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedQuestion : $this->msgError]);
    }

    public function deleteAnswerForm(Request $request){
        $id           = $request->input('id');
        $answer       = Answer::find($id);
        $form         = view('poll.forms.deleteFormAnswer', compact('answer'))->render();

        return response(['success' => $answer? true: false, 'view' => $form]);
    }

    public function deleteAnswer(Request $request){
        $id = $request->input('id');
        $success = Answer::destroy($id);
        return response(["success" => $success, "message" => $success ? $this->msgDeletedAnswer : $this->msgError]);
    }

    public function destroy(Poll $poll)
    {
        //
    }
}
