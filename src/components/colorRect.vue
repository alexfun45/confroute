<template>
	<div @click="setBackground" :style="{width:width+'px', height: height+'px', backgroundColor: currentColor}" class='colorRect'>	
	</div>
</template>
<script>
export default{
	data: function() {
		return {
			clrN: 0,
			currentColor: null		
		}	
	},
	props: {
		colors:{
			type: Array,
			//required: true,
			default: function(){ return ['red', 'blue']; }
			},
		width: {
			type: Number,
			default:30		
		},
		height:{
			type: Number,
			default: 30		
		}
	},
	mounted(){
		this.currentColor = this.colors[this.clrN];
	},
	methods:{
		setBackground(){
			//event.currentTarget.style.backgroundColor = this.colors[this.clrN];
			this.clrN = (this.clrN+1) % this.colors.length;
			this.currentColor = this.colors[this.clrN];		
		}	
	}
}
</script>
<style scoped>
</style>