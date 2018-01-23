<script>
/**
* Translation of CameraTag
* @author Troels Madsen
*/

if (typeof(CT_i18n) == "undefined") {
CT_i18n = []
}

@for($i = 0; $i < 111; $i++)
        CT_i18n[{{ $i }}] = CT_i18n[{{ $i }}] || '@lang('cameratag.'.$i)';
@endfor
</script>