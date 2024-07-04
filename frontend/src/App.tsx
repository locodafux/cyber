import { DarkThemeToggle } from "flowbite-react";
import { MyNav } from "./components/NavBar.tsx";

function App() {
  return (
    <main className="flex w-screen h-screen dark:bg-gray-800">
        <MyNav />
        <DarkThemeToggle className="h-fit"/>
    </main>
  );
}

export default App;
