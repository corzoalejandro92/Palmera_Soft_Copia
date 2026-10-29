<form action="#" id="pollForm">
    
    {{ method_field(isset($poll->id)?'PUT':'POST') }}
    {{ csrf_field() }}
    <input type="hidden" value="{{ $poll->id ?? '' }}" name="id" />
  
    <!-- Step 1 -->
    @include('poll.forms.steps.step1')

    <!-- Step 2 -->
    @include('poll.forms.steps.step2')

</form>