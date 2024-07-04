
import { Navbar } from "flowbite-react";  
import { DarkThemeToggle } from "flowbite-react";

export function MyNav() {
  return (
    <Navbar className="w-screen h-16 flex ">
   
     <DarkThemeToggle className="h-fit ml-auto"/>

    </Navbar>
  );
}

