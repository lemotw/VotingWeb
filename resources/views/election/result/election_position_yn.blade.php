@extends('election.layout')

<style>
    .result_table {
        padding: 20px;
        min-width: 50%;
        min-height: 400px;
        width: 60vw;
        height: auto;
        border-radius: 10px;
        box-shadow: 10px 10px 20px 10px rgba(0, 0, 0, 0.2);
        background-color: white;
        margin: 20px;
        text-align: center;

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .row {
        height: 40px;
    }

    .result_table > div > div > div{
        border: solid 1px;
        line-height: 40px;
    }
</style>

@section('title', $title)
@section('content')

<div class="result_table">

    <div class="container">

        <div class="row">
            <div class="col-8">{{ $vote_result->ElectionPositionEntity->Name }}</div>
            <div class="col-2">應投人數</div>
            <div class="col-2">{{ $vote_count }}</div>
        </div>

        <div class="row">
            <div class="col-4"></div>
            <div class="col-2">同意</div>
            <div class="col-2">不同意</div>
            <div class="col-2">無效票</div>
            <div class="col-2">總計</div>
        </div>

        <div class="row">
            <div class="col-4">得票數 (人)</div>
            <div class="col-2">{{ $vote_result->Yes }}</div>
            <div class="col-2">{{ $vote_result->No }}</div>
            <div class="col-2">{{ $vote_result->disable }}</div>
            <div class="col-2">{{ $vote_result->Yes + $vote_result->No + $vote_result->disable }}</div>
        </div>

        <div class="row">
            <div class="col-4">投票率 (%)</div>
            <div class="col-2">{{ round($vote_result->Yes/$vote_count * 10000)/100 }}%</div>
            <div class="col-2">{{ round($vote_result->No/$vote_count * 10000)/100 }}%</div>
            <div class="col-2">{{ round($vote_result->disable/$vote_count * 10000)/100 }}%</div>
            <div class="col-2">{{ round(($vote_result->Yes + $vote_result->No + $vote_result->disable)/$vote_count * 10000)/100 }}%</div>
        </div>

        <div class="row">
            <div class="col-4">得票率 (%)</div>
            <div class="col-2">{{ round($vote_result->Yes/($vote_result->Yes + $vote_result->No + $vote_result->disable) * 10000)/100 }}%</div>
            <div class="col-2">{{ round($vote_result->No/($vote_result->Yes + $vote_result->No + $vote_result->disable) * 10000)/100 }}%</div>
            <div class="col-2">{{ round($vote_result->disable/($vote_result->Yes + $vote_result->No + $vote_result->disable) * 10000)/100 }}%</div>
            <div class="col-2">100%</div>
        </div>

        <div class="row">
            <div class="col-4">備註</div>
            <div class="col-8"></div>
        </div>

    </div>

</div>



@endsection 