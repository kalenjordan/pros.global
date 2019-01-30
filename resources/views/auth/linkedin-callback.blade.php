@extends('app')

@section('script')
    <script type="text/javascript">
        let userData = JSON.stringify({!! $user ? json_encode($user->toArrayForCookie()) : null  !!});
    </script>
@endsection