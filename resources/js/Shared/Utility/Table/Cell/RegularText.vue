<template>
	{{ value != "" ? value : "-" }}
</template>

<script>
export default {
	props: {
		value: String | Number,
	},
};
</script>
