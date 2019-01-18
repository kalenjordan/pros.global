<template>
    <div>
        <!--<div style="display: none;">-->
             <!--&lt;!&ndash;v-shortkey="['h']"&ndash;&gt;-->
             <!--&lt;!&ndash;@shortkey="showKeyboardShortcuts()">&ndash;&gt;-->
        <!--</div>-->
        <modal name="help" >
            <h3 class="mb-2">Keyboard shortcuts</h3>
            <h4 class="mb-1">General</h4>
            <ul class="mb-4 ml-0 pl-4 leading-normal">
                <li><strong>h</strong> - Show keyboard shortcuts</li>
                <li><strong>Escape</strong> - Cancel editing</li>
                <li><strong>/</strong> - Search</li>
                <li><strong>Command + Enter</strong> - Submit a form you're typing in</li>
            </ul>
            <h4 class="mb-1">Profile page</h4>
            <ul class="ml-0 pl-4 leading-normal">
                <li><strong>e</strong> - Edit profile on profile page</li>
                <li><strong>Escape</strong> - Cancel editing</li>
            </ul>
        </modal>
    </div>
</template>
<script>
    export default {
        mounted() {
            window.addEventListener('keyup', this.hotkeys);
        },
        methods: {
            showKeyboardShortcuts() {
                this.$modal.show('help');
            },
            hotkeys(e) {
                if (document.activeElement.tagName === 'BODY') {
                    if (e.key === 'h') {
                        this.$router.push({name: 'home'});
                    }
                }
            },
        }
    }
</script>
