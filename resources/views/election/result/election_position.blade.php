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
            <div class="col-8">{{ $election_position->Name }}</div>
            <div class="col-2">應投人數</div>
            <div class="col-2">{{ $vote_count }}</div>
        </div>

        <div class="row">
            <div class="col-6">候選人</div>
            <div class="col-2">得票數</div>
            <div class="col-2">投票率</div>
            <div class="col-2">得票率</div>
        </div>

        @foreach($vote_result as $result)

            @if($result->Candidate == 'broken')
                @continue
            @endif

            <div class="row">
                <div class="col-6">{{ $result->CandidateEntity->Name }}</div>
                <div class="col-2">{{ $result->VoteCount }}</div>
                <div class="col-2">{{ round($result->VoteCount/$vote_count * 10000)/100 }}%</div>
                <div class="col-2">{{ round($result->VoteCount/$vote_sum* 10000)/100}}%</div>
            </div>
        @endforeach

        <div class="row">
            <div class="col-6">無效票</div>
            <div class="col-6">{{ $broken }}</div>
        </div>

        <div class="row">
            <div class="col-6">備註</div>
            <div class="col-6"></div>
        </div>

    </div>

</div>



@endsection 