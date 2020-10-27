import Dashboard from "./pages/Dashboard";
import CarParkStatus from "./pages/CarParkStatus";

export const routes = [
    // {
    //     path: "/",
    //     name: "index",
    //     component: IndexPage,
    //     meta: {
    //         title: "Home Page - APU Car Parking System",
    //         // metaTags: [
    //         //     {
    //         //         name: "description",
    //         //         content: "The home page of our example app."
    //         //     },
    //         //     {
    //         //         property: "og:description",
    //         //         content: "The home page of our example app."
    //         //     }
    //         // ]
    //     }
    // },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard
    },
    {
        path: "/car-park-status",
        name: "car_park_status",
        component: CarParkStatus
    },
];

