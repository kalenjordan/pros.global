<?php
/** @var \App\User $user */
?>

<html>
<body>
    <script type="text/javascript">
        opener.Events.$emit('user-authenticated', {!! Auth::user() ? Auth::user()->toJson() : null  !!});
        opener.user={!! Auth::user() ? Auth::user()->toJson() : null  !!};
        opener.api_token = "{{Auth::user() ? Auth::user()->api_token : "" }}";
        window.close();
    </script>
</body>
</html>