// Import the functions you need from the SDKs you need
const { initializeApp } = require("firebase/app");
const {
  getFirestore,
  collection,
  query,
  where,
  getDocs,
  updateDoc,
  increment,
  getDoc,
  doc,
  addDoc,
} = require("firebase/firestore");
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAbgGLvtiCs-9Nd43Dh14E5_Qn6uMbdoNk",
  authDomain: "sistem-social.firebaseapp.com",
  projectId: "sistem-social",
  storageBucket: "sistem-social.firebasestorage.app",
  messagingSenderId: "482595109492",
  appId: "1:482595109492:web:c55b113d18aee968c76f21",
  measurementId: "G-ND24H8Z494",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

let usuarios = new Set();

/**
 * Función para buscar un usuario por su user_id en Firestore
 * @param {number} userId - ID del usuario a buscar
 * @returns {Promise<Object|null>} - Retorna el usuario si se encuentra, de lo contrario null
 */

//Cargar todos los usuarios

async function restarCreditos(userId, cantidad) {
  try {
    const usersRef = collection(db, "usuarios");
    const q = query(usersRef, where("user_id", "==", String(userId)));
    const querySnapshot = await getDocs(q);

    if (querySnapshot.empty) {
      console.log("Usuario no encontrado.");
      return { status: "error", message: "Usuario no encontrado." };
    }

    const userDoc = querySnapshot.docs[0];
    const userRef = doc(db, "usuarios", userDoc.id);
    const userData = userDoc.data();

    if (userData.creditos < cantidad) {
      console.log("Créditos insuficientes.");
      return { status: "error", message: "Créditos insuficientes." };
    }

    await updateDoc(userRef, {
      creditos: increment(-cantidad),
    });

    console.log(`Se restaron ${cantidad} créditos al usuario ${userId}`);
    return {
      status: "success",
      message: `Se restaron ${cantidad} créditos al usuario ${userId}.`,
    };
  } catch (error) {
    console.error("Error al restar créditos:", error);
    return false;
  }
}

async function sumarCreditos(userId, cantidad) {
  try {
    const usersRef = collection(db, "usuarios");
    const q = query(usersRef, where("user_id", "==", String(userId)));
    const querySnapshot = await getDocs(q);

    if (querySnapshot.empty) {
      console.log("Usuario no encontrado.");
      return { status: "error", message: "Usuario no encontrado." };
    }

    const userDoc = querySnapshot.docs[0];
    const userRef = doc(db, "usuarios", userDoc.id);

    await updateDoc(userRef, {
      creditos: increment(cantidad),
    });

    console.log(`Se sumaron ${cantidad} créditos al usuario ${userId}`);
    return {
      status: "success",
      message: `Se sumaron ${cantidad} créditos al usuario ${userId}.`,
    };
    //return true;
  } catch (error) {
    console.error("Error al sumar créditos:", error);
    return false;
  }
}

async function cargarUsuarios() {
  try {
    const usersRef = collection(db, "usuarios");
    const querySnapshot = await getDocs(usersRef);

    querySnapshot.forEach((doc) => {
      const userData = doc.data();
      usuarios.add({ [doc.id]: userData.user_id });
    });

    console.log("Usuarios cargados en el Set:", usuarios);
  } catch (error) {
    console.error("Error al cargar usuarios:", error);
  }
}

async function getUserByEmail(datos) {
  try {
    console.log("Consultando usuario con email:", datos);

    // Asegurar que email sea un string válido
    const emailString = String(datos.email).trim().toLowerCase();

    const usersRef = collection(db, "usuarios");
    const q = query(usersRef, where("email", "==", emailString));
    const querySnapshot = await getDocs(q);

    if (!querySnapshot.empty) {
      const userData = querySnapshot.docs.map((doc) => doc.data());
      console.log("Se encontró el usuario XD.", userData[0].password);
      if (datos.password == userData[0].password) {
        return userData.length === 1 ? userData[0] : userData;
      } else {
      console.log("Contraseña incorrecta");

        return null;
      }
    } else {
      console.log("No se encontró el usuario XD.");
      return null;
    }
  } catch (error) {
    console.error("Error buscando usuario:", error);
    return null;
  }
}

async function registerUser(userData) {
  try {
    const usersRef = collection(db, "usuarios");

    // Verificar si el email ya existe
    const q = query(usersRef, where("email", "==", userData.email));
    const querySnapshot = await getDocs(q);

    if (!querySnapshot.empty) {
      console.log("El usuario con este email ya existe.");
      //querySnapshot.forEach((doc) => {
      //  usuarios.set(doc.id, userData.email); // Usar set en lugar de add para evitar duplicados
      //});

      return false;
    }

    // Agregar el campo "plan" con valor "Sin Plan"
    const newUser = {
      ...userData,
      plan: "Sin Plan",
    };

    const docRef = await addDoc(usersRef, newUser);
    //usuarios.set(docRef.id, userData.email); // Guardar con email asociado
    return true;
  } catch (error) {
    console.error("Error registrando usuario:", error);
    return null;
  }
}

async function registerVenta(userData) {
  try {
    const usersRef = collection(db, "ventas");

    // Verificar si el user_id ya existe
    //const q = query(usersRef, where("user_id", "==", userData.user_id));
    //const querySnapshot = await getDocs(q);
    //
    //if (!querySnapshot.empty) {
    //  console.log("El usuario con user_id ya existe.");
    //  querySnapshot.forEach((doc) => {
    //    usuarios.add({ [doc.id]: userData.user_id });
    //  });
    //
    //  return false;
    //}

    // Agregar el campo "plan" con valor "Sin Plan"
    const newUser = {
      ...userData,
      //plan: "Sin Plan",
    };

    //const docRef =
    await addDoc(usersRef, newUser);
    //console.log("Usuario registrado con ID:", docRef.id);
    //usuarios.add({ [docRef.id]: userData.user_id });
    return true;
  } catch (error) {
    //console.error("Error registrando usuario:", error);
    return null;
  }
}

//Buscar cuenta Netflix

async function buscarNetflix(userId, tipo) {
  try {
    console.log("Consultando usuario con ID:", userId);

    // Asegurar que userId sea un string
    const userIdString = String(userId);

    const usersRef = collection(db, "ventas");
    const q = query(usersRef, where("userID_Client", "==", userIdString));
    const querySnapshot = await getDocs(q);

    if (!querySnapshot.empty) {
      // Filtrar solo los documentos que sean de tipo "Netflix"
      const userData = querySnapshot.docs
        .map((doc) => doc.data())
        .filter((data) => data.tipo?.toLowerCase() === tipo);

      if (userData.length > 0) {
        // console.log("Datos Netflix:", userData);

        return userData.length === 1 ? userData[0] : userData;
      } else {
        console.log("No se encontraron datos de Netflix.");
        return null;
      }
    } else {
      console.log("No se encontró el usuario.");
      return null;
    }
  } catch (error) {
    console.error("Error buscando usuario:", error);
    return null;
  }
}

// Exportar función usando CommonJS
module.exports = {
  getUserByEmail,
  registerUser,
  restarCreditos,
  sumarCreditos,
  registerVenta,
  buscarNetflix,
  usuarios,
};
