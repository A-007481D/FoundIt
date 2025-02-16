import { RouterProvider } from "react-router-dom";
import { router } from "./router"; // No need for `/index.jsx`

function App() {
  return <RouterProvider router={router} />;
}

export default App;
