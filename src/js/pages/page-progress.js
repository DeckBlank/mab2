import Vue from 'vue'
import ApexCharts from 'apexcharts'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'

const progress = new Vue({
  ...baseConfig(store),
  data() {
    return {
      progresses: []
    }
  },
  computed: {
    ...baseState()
  },
  created(){
    if(!this.logedUser){
      window.location = `${this.SITE_URL}/login`;
    }
  },
  beforeMount(){
    this.initSectors();
  },
  mounted(){
    this.getProgresses();
    this.hideLoading();
  },
  methods: {
    ...baseActions(),
    initCharts: function(){
      let options = {
        chart: {
          type: 'pie',
          width: 300,
          height: 300       
        },
        stroke: {
          show: false
        },
        dataLabels: {
          style: {
            fontSize: '25px',
            fontFamily: 'DINRoundPro, Arial, sans-serif',
            fontWeight: 'bold',
            colors: ['#fbee23']
          },
          dropShadow: {
            enabled: false
          }
        },
        colors: ['#f32e21', '#fbee23'],
        legend: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        states: {
          hover: {
            filter: {
              type: 'none'
            }
          },
          active: {
            filter: {
              type: 'none',
              value: 0
            }
          }     
        }     
      }
      
      document.querySelectorAll(".c-chart__graph").forEach((graph)=>{
        let graph_progress = graph.dataset.progress;
  
        (new ApexCharts(graph, {
          ...options,
          series: [parseFloat(graph_progress), 100 - parseFloat(graph_progress)],
          plotOptions: {
            pie: {
              expandOnClick: false,
              dataLabels: {
                offset: (parseFloat(graph_progress) == 100) ? -100 : -30
              }          
            }
          }         
        })).render()
      })
    },
    getProgresses: function(){
      fetch(`${this.API}/courses/progress?user=${this.logedUser.user_email}`,{
          method: 'GET'
        })
        .then(res => {
          if (res.status >= 200 && res.status < 300) {
            return res.json()
          }else{
            throw res
          }
        })
        .then(progresses => {
          this.progresses = progresses;
          window.setTimeout(()=>{
            this.initCharts()
          },100)
        })
        .catch(err => {
          window.location = `${this.SITE_URL}/solicitar-cursos`;
          
          throw err;          
        })      
    }
  }
})
