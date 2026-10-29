<form action="#" id="quoteForm">
    {{ method_field( isset($quote->id)?'PUT':'POST') }}
    {{ csrf_field() }}
    <input type="hidden" value="<?= $quote->id ?? ''; ?>" name="id" />

    @include('layouts.loading.wizard')
    <!-- Step 1 -->
    @include('quote.forms.steps.step1')

    <!-- Step 2 -->
    @include('quote.forms.steps.step2')

    <!-- Step 3 -->
    @include('quote.forms.steps.step3')

    <!-- Step 4 -->
    @include('quote.forms.steps.step4')

    <!-- Step 5 -->
    {{-- @include('quote.forms.steps.attachFile')
    @include('quote.forms.steps.step5') --}}
</form>