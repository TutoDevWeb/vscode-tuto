import React, { useState } from 'react';
import Ariane from './components/Ariane';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import VoirListeTuto from './components/VoirListeTuto';
import help from '../images/help.png';
import * as admin_liste_de_quizz from './API-PHP/admin-liste-de-quizz.js';

const E06_CREER_UN_QUIZ = (props) => {
	const debug = props.app_debug;

	const [quizz_name, set_quizz_name] = useState('');
	const [quizz_cacher, set_quizz_cacher] = useState(0);
	const [quizz_on_tuto_id, set_quizz_on_tuto_id] = useState('');

	const handleAnnuler = () => props.goEcran('E01_ACCUEIL');

	const handleEnregistrer = () => {
		debug && console.log('Créer le Quiz');
		if (
			quizz_name !== '' &&
			quizz_on_tuto_id !== '' &&
			quizz_cacher !== ''
		) {
			admin_liste_de_quizz.add_quizz(
				props.chapitre_id,
				quizz_name,
				quizz_cacher,
				quizz_on_tuto_id
			);
			props.goEcran('E01_ACCUEIL');
		} else toast.error('Il faut remplir les champs');
	};

	const handleQuizCacher = (e) => {
		debug && console.log(e.target.value);
		debug && console.log(typeof e.target.value);
		if (e.target.value === '0') set_quizz_cacher(1);
		else set_quizz_cacher(0);
	};

	return (
		<div>
			<ToastContainer
				position="top-right"
				autoClose={3000}
				hideProgressBar={true}
				newestOnTop={false}
				closeOnClick
				rtl={false}
				pauseOnFocusLoss
				draggable
				pauseOnHover
			/>
			<Ariane
				app_debug={props.app_debug}
				labels="Accueil|Créer un quiz"
				numEcran="E01_ACCUEIL|E06_CREER_UN_QUIZ"
				goEcran={props.goEcran}
				ecran="E06"
			/>
			<header className="ecran-header">
				<p>Création d'un quiz</p>
				<p>
					chapitre_name : {props.chapitre_name} (chapitre_id=
					{props.chapitre_id})
				</p>
			</header>
			<article>
				<div className="QuizCard">
					<div>quizz_name (48 max)</div>
					<input
						value={quizz_name}
						size="30"
						maxLength="48"
						onChange={(e) => set_quizz_name(e.target.value)}
					/>

					<div>
						quizz_on_tuto_id (128 max)
						<img
							src={help}
							alt="help"
							title="Liste des tuto_id des tuto sur lesquels porte le quizz. Les séparer par le caractère pipe | "
						/>
					</div>
					<input
						size="30"
						maxLength="128"
						value={quizz_on_tuto_id}
						onChange={(e) => set_quizz_on_tuto_id(e.target.value)}
					/>
					<div>quizz_cacher</div>
					<input
						type="checkbox"
						value={quizz_cacher}
						onChange={handleQuizCacher}
						checked={quizz_cacher}
					/>

					<VoirListeTuto
						app_debug={props.app_debug}
						chapitre_id={props.chapitre_id}
					/>
				</div>
			</article>
			<nav>
				<button onClick={handleAnnuler}>Retour Accueil</button>
				<button onClick={handleEnregistrer}>Enregistrer</button>
			</nav>
		</div>
	);
};
export default E06_CREER_UN_QUIZ;
