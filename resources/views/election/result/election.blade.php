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
        text-align: center;

        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }

    .container{
        margin: 20px;
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


@foreach ($vote_result_list as $result)

    @if($result['vote_result']->count() > 1)
    <div class="container">

        <div class="row">
            <div class="col-8">{{ $result['vote_result'][0]->ElectionPositionEntity->Name }}</div>
            <div class="col-2">應投人數</div>
            <div class="col-2">{{ $result['vote_count'] }}</div>
        </div>

        <div class="row">
            <div class="col-6">候選人</div>
            <div class="col-2">得票數</div>
            <div class="col-2">投票率</div>
            <div class="col-2">得票率</div>
        </div>

        @foreach($result['vote_result'] as $cand)

            @if($cand->Candidate == 'broken')
                @continue
            @endif

            <div class="row">
                <div class="col-6">{{ $cand->CandidateEntity->Name }}</div>
                <div class="col-2">{{ $cand->VoteCount }}</div>
                <div class="col-2">{{ round($cand->VoteCount/$result['vote_count'] * 10000)/100 }}%</div>
                <div class="col-2">{{ round($cand->VoteCount/$result['vote_sum']* 10000)/100}}%</div>
            </div>
        @endforeach

        <div class="row">
            <div class="col-6">無效票</div>
            <div class="col-6">{{ $result['broken'] }}</div>
        </div>

        <div class="row">
            <div class="col-6">備註</div>
            <div class="col-6"></div>
        </div>

    </div>
    @elseif($result['vote_result']->count() > 0)
    <div class="container">

        <div class="row">
            <div class="col-8">{{ $result['vote_result'][0]->ElectionPositionEntity->Name }}</div>
            <div class="col-2">應投人數</div>
            <div class="col-2">{{ $result['vote_count'] }}</div>
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
            <div class="col-2">{{ $result['vote_result'][0]->Yes }}</div>
            <div class="col-2">{{ $result['vote_result'][0]->No }}</div>
            <div class="col-2">{{ $result['vote_result'][0]->disable }}</div>
            <div class="col-2">{{ $result['vote_result'][0]->Yes + $result['vote_result'][0]->No + $result['vote_result'][0]->disable }}</div>
        </div>

        <div class="row">
            <div class="col-4">投票率 (%)</div>
            <div class="col-2">{{ round($result['vote_result'][0]->Yes/$result['vote_count'] * 10000)/100 }}%</div>
            <div class="col-2">{{ round($result['vote_result'][0]->No/$result['vote_count'] * 10000)/100 }}%</div>
            <div class="col-2">{{ round($result['vote_result'][0]->disable/$result['vote_count'] * 10000)/100 }}%</div>
            <div class="col-2">{{ round(($result['vote_result'][0]->Yes + $result['vote_result'][0]->No + $result['vote_result'][0]->disable)/$result['vote_count'] * 10000)/100 }}%</div>
        </div>

        <div class="row">
            <div class="col-4">得票率 (%)</div>
            <div class="col-2">{{ round($result['vote_result'][0]->Yes/($result['vote_result'][0]->Yes + $result['vote_result'][0]->No + $result['vote_result'][0]->disable) * 10000)/100 }}%</div>
            <div class="col-2">{{ round($result['vote_result'][0]->No/($result['vote_result'][0]->Yes + $result['vote_result'][0]->No + $result['vote_result'][0]->disable) * 10000)/100 }}%</div>
            <div class="col-2">{{ round($result['vote_result'][0]->disable/($result['vote_result'][0]->Yes + $result['vote_result'][0]->No + $result['vote_result'][0]->disable) * 10000)/100 }}%</div>
            <div class="col-2">100%</div>
        </div>

        <div class="row">
            <div class="col-4">備註</div>
            <div class="col-8"></div>
        </div>

    </div>
    @endif
@endforeach

</div>



@endsection 