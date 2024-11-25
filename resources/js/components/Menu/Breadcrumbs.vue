<template>
    <nav
        v-if="hasItems"
        class="text-gray-500 font-semibold"
        aria-label="breadcrumb"
        dusk="breadcrumbs"
    >
        <ol class="flex items-center">
            <li
                v-for="(item, index) in breadcrumbs"
                v-bind="{
          'aria-current': index === breadcrumbs.length - 1 ? 'page' : null,
        }"
            >
                <div class="flex items-center">
                    <Link
                        :href="$url(item.path)"
                        v-if="item.path !== null && index < breadcrumbs.length - 1"
                        class="link-default flex gap-1"
                    >
                        <template v-if="item.isHtml">
                            <span v-html="item.name"></span>
                        </template>
                        <template v-else-if="item.isIcon">
                            <template v-if="item.prepend">{{ item.prepend }}</template>
                            <component :is="`heroicons-outline-${item.name}`" class="inline-block w-4 h-4" width="24" height="24"/>
                            <template v-if="item.append">{{ item.append }}</template>
                        </template>
                        <template v-else>
                            {{ item.name }}
                        </template>
                    </Link>
                    <span v-else>
                        <template v-if="item.isHtml">
                            <span v-html="item.name"></span>
                        </template>
                        <template v-else-if="item.isIcon">
                            <component :is="`heroicons-outline-${item.name}`" width="24" height="24"/>
                        </template>
                        <template v-else>
                            {{ item.name }}
                        </template>
                    </span>
                    <Icon
                        type="chevron-right"
                        v-if="index < breadcrumbs.length - 1"
                        class="w-4 h-4 mx-2 text-gray-300 dark:text-gray-700"
                    />
                </div>
            </li>
        </ol>
    </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters(['breadcrumbs']),

        hasItems() {
            return this.breadcrumbs.length > 0
        },
    },
}
</script>
