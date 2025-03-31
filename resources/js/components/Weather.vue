<script setup>
import { storeToRefs } from 'pinia';
import { useWeatherStore } from '../store/weather';
import { onMounted } from 'vue';

const weatherStore = useWeatherStore();
const { weatherData } = storeToRefs(weatherStore);

</script>

<template>
    <div>
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-info">
                <h3 class="widget-user-username">{{ weatherData?.location_data?.timezone }}</h3>
                <h5 class="widget-user-desc">Weather Today</h5>
            </div>
            <div class="widget-user-image">
                <h4 class="">{{ weatherData?.today_data?.summary }} <i class="fa fa-thermometer-full "></i></h4>
                
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header"> {{ weatherData?.today_data?.temperature }}°</h5>
                            <span class="description-text">Temperature</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{weatherData?.today_data?.wind_speed}}-{{weatherData?.today_data?.wind_dir}}-{{weatherData?.today_data?.wind_angle}}</h5>
                            <span class="description-text">Wind</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header">{{weatherData?.today_data?.cloud_cover_total}}-{{weatherData?.today_data?.precipitation_total}}-{{weatherData?.today_data?.precipitation_type}}</h5>
                            <span class="description-text">Cloud</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
</template>