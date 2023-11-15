import { ref, inject,reactive } from 'vue'


export default function resolutionProcedure()
{
    const inputCount = reactive({key_num: []})

    async function fnAddRowResolution(){
        inputCount.key_num.push({ valueNewResolution: []})
        console.log(inputCount);
    }

    async function fnRemoveRowResolution(index){
        inputCount.key_num.splice(index,1);
        console.log(inputCount);
    }

    return {
        fnAddRowResolution,
        fnRemoveRowResolution,
        inputCount,
    }
}
