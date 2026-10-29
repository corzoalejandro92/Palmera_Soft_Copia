<?php

/******************************************************************************/
/**Vista index*/
Route::get('poll', 'Administrative\PollController@index')
->name('polls.index')
->middleware('can:polls.index');

Route::get('polls/{poll}', 'Administrative\PollController@edit')
    ->name('polls.edit')
    ->middleware('can:polls.edit');

/******************************************************************************/
/**Lista de encuestas*/
Route::get('poll/list', 'Administrative\PollController@list')
->name('polls.list')
->middleware('can:polls.index');



/******************************************************************************/
/**Vista crear*/
Route::get('poll/form-poll', 'Administrative\PollController@create')
->name('polls.create')
->middleware('can:polls.create');

/******************************************************************************/
/**Guardar*/
Route::post('poll/save', 'Administrative\PollController@store')
    ->name('polls.store')
    ->middleware('can:polls.create');

/**Vista crear*/
Route::get('poll/index/result', 'Administrative\PollController@listResult')
->name('polls.list.resultSurvey')
->middleware('can:polls.survey.answer.index');

Route::get('poll/result/survey/show', 'Administrative\PollController@resultSurvay')
->name('polls.result.survey')
->middleware('can:polls.survey.answer.index');

/**Actualizar*/
Route::put('poll/save', 'Administrative\PollController@update')
    ->name('polls.update')
    ->middleware('can:polls.edit');

/******************************************************************************/
/**agregar una fila nueva de respuesta */
Route::get('poll/add-answer-row', 'Administrative\PollController@addAnswerRow')
->name('polls.addAnswerRow')
->middleware('can:polls.create');

/**Formulario para eliminar un pregunta*/
Route::get('poll/delete-answer-form','Administrative\PollController@deleteAnswerForm')
->name('polls.deleteAnswer')
->middleware('can:polls.create');

/**ELiminar la pregunta*/
Route::delete('poll/delete-answer', 'Administrative\PollController@deleteAnswer')
->name('polls.deleteAnswer')
->middleware('can:polls.create');

/******************************************************************************/

/**agregar una fila nueva de pregunta */
Route::get('poll/add-question-row', 'Administrative\PollController@addQuestionRow')
->name('polls.addQuestionRow')
->middleware('can:polls.create');

/**Formulario para eliminar un pregunta*/
Route::get('poll/delete-question-form','Administrative\PollController@deleteQuestionForm')
->name('polls.deleteQuestion')
->middleware('can:polls.create');

/**ELiminar la pregunta*/
Route::delete('poll/delete-question', 'Administrative\PollController@deleteQuestion')
->name('polls.deleteQuestion')
->middleware('can:polls.create');

/******************************************************************************/

/**Eliminar*/
Route::get('poll/delete-form', 'Administrative\PollController@deleteForm')
->name('polls.deleteform')
->middleware('can:polls.destroy');

Route::delete('poll/delete', 'Administrative\PollController@softDelete')
->name('polls.destroy')
->middleware('can:polls.destroy');

Route::get('poll/delete/survey', 'Administrative\PollController@softDeleteSurvey')
->name('polls.survey.destroy')
->middleware('can:polls.destroy');

/******************************************************************************/