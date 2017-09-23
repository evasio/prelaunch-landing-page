<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{

	/**
	 * @var Nette\Database\Context
	 * @inject
	 */
	public $db;

	/**
	 * @var string
	 * @persistent
	 */
	public $ref;



	/**
	 * @param string $ref
	 */
	public function actionDefault($ref)
	{
	}

	/**
	 * @param string $code
	 */
	public function actionThankYou($code)
	{
		if (!$code) {
			$this->redirect('default');
		}

		$dbInvitation = $this->db->table('signup')->where('ref_code', $code)->fetch();
		if (!$dbInvitation) {
			$this->redirect('default');
		}

		$refCode = strtoupper($code);
		$refUrl = $this->link('//Homepage:default', ['ref' => $refCode]);
		$twitterUrl = 'https://twitter.com/intent/tweet?text='.urlencode('My awesome prelaunch landing page').'&url='.urlencode($refUrl);
		$facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u='.$refUrl;

		$this->template->twitterUrl = $twitterUrl;
		$this->template->facebookUrl = $facebookUrl;
		$this->template->refUrl = $refUrl;
	}



	/**
	 * @return Nette\Application\UI\Form
	 */
	public function createComponentSignUpForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addHidden('token', base64_encode(time()));
		$form->addHidden('ref', $this->ref);
		$form->addText('email', NULL, NULL, 50)
			->setType('email')
			->setAttribute('placeholder', 'Enter email address')
			->setAttribute('onblur', 'this.focus();')
			->setRequired('Please enter email address.')
			->addRule($form::EMAIL, 'Please enter valid email address.')
			->addRule($form::MAX_LENGTH, 'Your email is too long (over %d characters).', 50);
		$form->addSubmit('signUpBtn', 'Join Beta!');

		$form->onSuccess[] = function($form, $values) {
			try {
				$token = @base64_decode($values->token);
				if (!$token || !is_numeric($token) || (time()-$token < 3)) {
					return;
				}

				$email = trim($values->email);
				$dbInvitation = $this->db->table('signup')->where('email', $email)->fetch();

				// skip if email is already in DB
				if (!$dbInvitation) {
					$this->db->beginTransaction();
					$idRef = NULL;

					// check referral code
					if ($values->ref) {
						$refCode = strtolower(substr(trim($values->ref), 0, 10));
						$dbRef = $this->db->table('signup')->where('ref_code', $refCode)->fetch();
						if ($dbRef) {
							$idRef = $dbRef->id;
							$dbRef->update([
								'ref_count' => $dbRef->ref_count + 1,
							]);
						}
					}

					$dbInvitation = $this->db->table('signup')->insert([
						'id_ref' => $idRef,
						'ref_code' => Nette\Utils\Random::generate(10, '0-9a-z'),
						'ref_count' => 0,
						'email' => $values->email,
						'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? NULL,
						'ip' => $_SERVER['REMOTE_ADDR'] ?? NULL,
						'inserted_at' => date('Y-m-d H:i:s'),
					]);
					$this->db->commit();
				}

				$code = $dbInvitation->ref_code;
				$this->redirect('thankYou', [
					'code' => strtoupper($code),
					'ref' => NULL,
				]);

			} catch (\Exception $e) {
				if ($e instanceof Nette\Application\AbortException) {
					throw $e;
				}

				try {
					$this->db->rollBack();
				} catch (\Exception $ee) {
					\Tracy\Debugger::log($ee, \Tracy\Debugger::CRITICAL);
				}

				$form->addError('Unexpected error occured. Please try it later.');
				$this->redirect('this');
			}
		};

		return $form;
	}

}
